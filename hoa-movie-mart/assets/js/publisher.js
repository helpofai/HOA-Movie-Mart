jQuery(document).ready(function($) {
    
    // --- 1. Content Type UI Toggle ---
    function toggleTypeFields(type) {
        if (type === 'tv') {
            $('.tv-only').slideDown();
        } else {
            $('.tv-only').slideUp();
        }
    }

    $('#content_type').on('change', function() {
        toggleTypeFields($(this).val());
    });

    // --- 2. Main Fetch Button (Smart Fallback) ---
    $('#btn-fetch').on('click', function() {
        var title = $('#fetch_title').val();
        var omdbKey = hoaData.omdbKey;
        var tmdbKey = hoaData.tmdbKey;
        var btn = $(this);

        if (!title) {
            alert('Please enter a title.');
            return;
        }

        // Logic: Try TMDB first (better images), fallback to OMDB if TMDB fails or key missing
        if (tmdbKey) {
            fetchTMDB(title, tmdbKey, btn);
        } else if (omdbKey) {
            fetchOMDB(title, omdbKey, btn);
        } else {
            alert('No API Keys found! Please check your API Management settings.');
        }
    });

    // --- 3. Dedicated Fetch Buttons ---
    $('#btn-fetch-omdb').on('click', function() {
        var title = $('#fetch_title_omdb').val() || $('#fetch_title').val();
        if (!title) return alert('Enter a title for OMDB');
        fetchOMDB(title, hoaData.omdbKey, $(this));
    });

    $('#btn-fetch-tv').on('click', function() {
        var title = $('#fetch_title_tv').val() || $('#fetch_title').val();
        if (!title) return alert('Enter a series name for TVmaze');
        fetchTVmaze(title, $(this));
    });

    // --- 4. API Core Functions ---

    function fetchTMDB(title, apiKey, btn) {
        btn.text('Fetching TMDB...').prop('disabled', true);
        $.getJSON(`https://api.themoviedb.org/3/search/multi?api_key=${apiKey}&query=${encodeURIComponent(title)}`, function(data) {
            if (data.results && data.results.length > 0) {
                var first = data.results[0];
                var type = (first.media_type === 'tv') ? 'tv' : 'movie';
                var id = first.id;

                $.getJSON(`https://api.themoviedb.org/3/${type}/${id}?api_key=${apiKey}&append_to_response=credits,external_ids,content_ratings,release_dates`, function(details) {
                    btn.text('Fetch TMDB').prop('disabled', false);
                    $('#content_type').val(type).trigger('change'); 

                    // Store ID for Episode Fetcher
                    $('#fetch_title').data('tmdb-id', id);

                    var cast = details.credits.cast.slice(0,5).map(c => c.name).join(', ');
                    var genres = details.genres.map(g => g.name).join(', ');
                    
                    // Director Logic (TV shows have 'created_by')
                    var director = '';
                    if (type === 'tv' && details.created_by && details.created_by.length > 0) {
                        director = details.created_by.map(c => c.name).join(', ');
                    } else if (details.credits.crew) {
                        var dObj = details.credits.crew.find(c => c.job === 'Director');
                        director = dObj ? dObj.name : '';
                    }

                    // Rating Logic (Certification)
                    var rated = '';
                    if (type === 'movie' && details.release_dates) {
                        var us = details.release_dates.results.find(r => r.iso_3166_1 === 'US');
                        if (us && us.release_dates[0]) rated = us.release_dates[0].certification;
                    } else if (type === 'tv' && details.content_ratings) {
                        var us = details.content_ratings.results.find(r => r.iso_3166_1 === 'US');
                        if (us) rated = us.rating;
                    }

                    populateFields({
                        title: details.title || details.name,
                        plot: details.overview,
                        rating: details.vote_average,
                        runtime: (type === 'tv') ? (details.episode_run_time[0] || '45') + ' min' : details.runtime + ' min',
                        year: (details.release_date || details.first_air_date || '').substring(0, 4),
                        release_date: details.release_date || details.first_air_date || '',
                        genre: genres,
                        language: details.original_language.toUpperCase(),
                        poster: details.poster_path ? 'https://image.tmdb.org/t/p/w500' + details.poster_path : '',
                        actors: cast,
                        director: director,
                        imdb_id: details.external_ids ? details.external_ids.imdb_id : '',
                        seasons: details.number_of_seasons || '',
                        status: details.status,
                        rated: rated,
                        country: details.origin_country ? details.origin_country.join(', ') : (details.production_countries ? details.production_countries[0].iso_3166_1 : ''),
                        studio: details.networks ? details.networks[0].name : (details.production_companies[0] ? details.production_companies[0].name : '')
                    });
                });
            } else {
                btn.text('Fetch TMDB').prop('disabled', false);
                if (hoaData.omdbKey) {
                    console.log('TMDB failed/empty. Falling back to OMDB...');
                    fetchOMDB(title, hoaData.omdbKey, btn);
                } else {
                    alert('Not found on TMDB.');
                }
            }
        }).fail(() => { 
            btn.text('Fetch TMDB').prop('disabled', false); 
            if (hoaData.omdbKey) fetchOMDB(title, hoaData.omdbKey, btn);
            else alert('TMDB Error'); 
        });
    }

    function fetchOMDB(title, apiKey, btn) {
        btn.text('Fetching OMDB...').prop('disabled', true);
        $.getJSON(`https://www.omdbapi.com/?t=${encodeURIComponent(title)}&apikey=${apiKey}`, function(data) {
            btn.text('Fetch OMDB').prop('disabled', false);
            if (data.Response === "False") return alert(data.Error);

            var type = (data.Type === 'series') ? 'tv' : 'movie';
            $('#content_type').val(type).trigger('change');

            populateFields({
                title: data.Title,
                plot: data.Plot,
                rating: data.imdbRating,
                runtime: data.Runtime,
                year: data.Year ? data.Year.substring(0, 4) : '',
                release_date: data.Released !== 'N/A' ? new Date(data.Released).toISOString().split('T')[0] : '',
                genre: data.Genre,
                language: data.Language,
                poster: (data.Poster !== 'N/A') ? data.Poster : '',
                director: data.Director,
                actors: data.Actors,
                imdb_id: data.imdbID,
                seasons: data.totalSeasons || '',
                status: (type === 'tv' && data.Year.includes('–')) ? 'Running' : (type === 'tv' ? 'Ended' : ''), // Heuristic for status
                rated: data.Rated,
                country: data.Country,
                studio: data.Production || ''
            });
        }).fail(() => { btn.text('Fetch OMDB').prop('disabled', false); alert('OMDB Error'); });
    }

    function fetchTVmaze(title, btn) {
        btn.text('Fetching TVmaze...').prop('disabled', true);
        $.getJSON(`https://api.tvmaze.com/singlesearch/shows?q=${encodeURIComponent(title)}&embed=cast`, function(data) {
            btn.text('Fetch TVmaze').prop('disabled', false);
            if (!data) return alert('Not found');

            $('#content_type').val('tv').trigger('change'); // Auto-Switch

            populateFields({
                title: data.name,
                plot: data.summary ? data.summary.replace(/<\/?[^>]+(>|$)/g, "") : "",
                rating: data.rating.average,
                runtime: data.averageRuntime + ' min',
                year: data.premiered ? data.premiered.substring(0,4) : '',
                genre: data.genres.join(', '),
                language: data.language,
                poster: data.image ? data.image.original : '',
                actors: data._embedded.cast.slice(0,5).map(c => c.person.name).join(', '),
                imdb_id: (data.externals && data.externals.imdb) ? data.externals.imdb : '',
                seasons: '',
                status: data.status
            });
        });
    }

    function populateFields(d) {
        $('#movie_title').val(d.title);
        $('#movie_short_desc').val(d.plot);
        $('#imdb_rating').val(d.rating);
        $('#runtime').val(d.runtime);
        $('#movie_year').val(d.year);
        $('#movie_genres').val(d.genre);
        $('#movie_director').val(d.director);
        $('#movie_cast').val(d.actors);
        $('#language').val(d.language);
        $('#imdb_id').val(d.imdb_id);
        $('#total_seasons').val(d.seasons);
        $('#series_status').val(d.status);
        $('#release_date').val(d.release_date);
        $('#parental_rating').val(d.rated);
        $('#country').val(d.country);
        $('#studio').val(d.studio);

        if (typeof tinyMCE !== 'undefined' && tinyMCE.get('movie_content')) {
            tinyMCE.get('movie_content').setContent(`<p>${d.plot}</p>`);
        }
        if (d.poster) {
            $('#poster_url_external').val(d.poster);
            $('#poster-preview').html(`<img src="${d.poster}">`);
        }
        
        $('#movie_keywords').val([d.title, d.year, d.genre, d.actors].join(', '));
        updateContentScore();
    }

    // --- 5. Content Score ---
    function updateContentScore() {
        var s = 0;
        if ($('#movie_title').val()) s += 10;
        if ($('#movie_short_desc').val()) s += 10;
        if ($('#imdb_rating').val()) s += 10;
        if ($('#poster_url_external').val() || $('#poster_image_id').val()) s += 20;
        if ($('#download-repeater .download-row').length > 0) s += 20;
        if ($('#movie_gallery_ids').val()) s += 10;
        if ($('#imdb_id').val()) s += 20;

        $('#score-value').text(s + '%');
        $('#score-fill').css('width', s + '%').css('background', s < 50 ? 'red' : (s < 80 ? 'orange' : 'green'));
    }

    $(document).on('input change', 'input, textarea', updateContentScore);

    // --- 6. Uploader Logic ---
    function setupMediaUploader(btnId, inputId, previewId, multiple = false) {
        $(btnId).on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({ title: 'Select Media', button: { text: 'Use' }, multiple: multiple });
            frame.on('select', function() {
                if (!multiple) {
                    var att = frame.state().get('selection').first().toJSON();
                    $(inputId).val(att.id);
                    $(previewId).html(`<img src="${att.url}">`);
                    $('#poster_url_external').val('');
                } else {
                    var sel = frame.state().get('selection');
                    var ids = $(inputId).val() ? $(inputId).val().split(',') : [];
                    sel.map(a => {
                        a = a.toJSON();
                        if (ids.indexOf(a.id.toString()) === -1) {
                            ids.push(a.id);
                            $(previewId).append(`<div class="gallery-item" data-id="${a.id}" style="position:relative;"><img src="${a.url}" style="width:100%;height:60px;object-fit:cover;border-radius:4px;"><span class="remove-gallery-item" style="position:absolute;top:-5px;right:-5px;background:red;color:white;border-radius:50%;width:16px;height:16px;cursor:pointer;text-align:center;line-height:16px;">&times;</span></div>`);
                        }
                    });
                    $(inputId).val(ids.join(','));
                }
                updateContentScore();
            });
            frame.open();
        });
    }

    setupMediaUploader('#upload-poster', '#poster_image_id', '#poster-preview');
    setupMediaUploader('#upload-gallery', '#movie_gallery_ids', '#gallery-preview', 'add');

    $(document).on('click', '.remove-gallery-item', function() {
        var id = $(this).closest('.gallery-item').data('id').toString();
        var ids = $('#movie_gallery_ids').val().split(',').filter(i => i !== id);
        $('#movie_gallery_ids').val(ids.join(','));
        $(this).closest('.gallery-item').remove();
        updateContentScore();
    });

    // --- 7. Download Repeater ---
    function addLink(l='', q='', u='', s='') {
        var i = $('#download-repeater .download-row').length;
        $('#download-repeater').append(`<div class="download-row">
            <input type="text" name="download_links[${i}][label]" value="${l}" placeholder="Server" style="flex:1">
            <input type="text" name="download_links[${i}][quality]" value="${q}" placeholder="Quality" style="width:80px;">
            <input type="text" name="download_links[${i}][size]" value="${s}" placeholder="Size" style="width:80px;">
            <input type="url" name="download_links[${i}][url]" value="${u}" placeholder="URL" style="flex:2">
            <button type="button" class="button remove-row"><span class="dashicons dashicons-trash"></span></button>
        </div>`);
    }
    $('#add-download-row').on('click', () => addLink());
    $(document).on('click', '.remove-row', function() { $(this).closest('.download-row').remove(); updateContentScore(); });
    if ($('#download-repeater').children().length === 0) addLink('Google Drive', '720p', '', '1.2 GB');

    // --- 8. Season & Episode Manager ---
    var seasonsData = [];

    function renderSeasons() {
        var html = '';
        seasonsData.forEach((season, sIndex) => {
            html += `
            <div class="season-box" data-index="${sIndex}" style="background:#f6f7f7; border:1px solid #dcdcde; margin-bottom:15px; border-radius:4px;">
                <div class="season-header" style="padding:10px; background:#fff; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; cursor:pointer;">
                    <h4 style="margin:0;">Season ${season.number} (${season.episodes.length} Episodes)</h4>
                    <div style="display:flex; gap:5px; align-items:center;">
                        <input type="text" class="season-zip" placeholder="Zip/Batch URL" value="${season.zip || ''}" style="width:120px; font-size:11px;" onclick="event.stopPropagation()" onchange="updateSeason(${sIndex}, 'zip', this.value)">
                        <button type="button" class="button button-small bulk-link-btn" data-index="${sIndex}" title="Paste multiple links at once">Bulk Links</button>
                        <button type="button" class="button button-small add-episode-btn" data-index="${sIndex}">+ Ep</button>
                        <button type="button" class="button button-small button-link-delete remove-season-btn" data-index="${sIndex}">&times;</button>
                    </div>
                </div>
                <div class="season-body" style="padding:10px;">
                    ${season.episodes.map((ep, eIndex) => `
                        <div class="episode-row" style="display:flex; gap:10px; margin-bottom:8px; align-items:center;">
                            <span style="font-weight:bold; color:#666; width:30px;">#${ep.number}</span>
                            <div style="width:50px; height:30px; background:#ddd; overflow:hidden;">
                                ${ep.img ? `<img src="${ep.img}" style="width:100%; height:100%; object-fit:cover;">` : ''}
                            </div>
                            <input type="hidden" class="ep-img" value="${ep.img || ''}">
                            <input type="text" class="ep-title" value="${ep.title}" placeholder="Title" style="flex:2" onchange="updateEpisode(${sIndex}, ${eIndex}, 'title', this.value)">
                            <input type="text" class="ep-date" value="${ep.date}" placeholder="Date" style="width:100px" onchange="updateEpisode(${sIndex}, ${eIndex}, 'date', this.value)">
                            <input type="text" class="ep-link" value="${ep.link}" placeholder="Download URL" style="flex:2" onchange="updateEpisode(${sIndex}, ${eIndex}, 'link', this.value)">
                            <span class="dashicons dashicons-trash remove-episode-btn" style="cursor:pointer; color:#a00;" onclick="removeEpisode(${sIndex}, ${eIndex})"></span>
                        </div>
                    `).join('')}
                </div>
            </div>`;
        });
        $('#seasons-wrapper').html(html);
        $('#seasons_data').val(JSON.stringify(seasonsData));
    }

    // Expose functions to global scope for inline onclicks (simple approach)
    window.updateSeason = function(sIdx, field, val) {
        seasonsData[sIdx][field] = val;
        $('#seasons_data').val(JSON.stringify(seasonsData));
    };

    window.updateEpisode = function(sIdx, eIdx, field, val) {
        seasonsData[sIdx].episodes[eIdx][field] = val;
        $('#seasons_data').val(JSON.stringify(seasonsData));
    };

    window.removeEpisode = function(sIdx, eIdx) {
        seasonsData[sIdx].episodes.splice(eIdx, 1);
        renderSeasons();
    };

    $('#add-season').on('click', function() {
        var nextNum = seasonsData.length + 1;
        seasonsData.push({ number: nextNum, episodes: [] });
        renderSeasons();
    });

    $(document).on('click', '.remove-season-btn', function() {
        var idx = $(this).data('index');
        if(confirm('Remove this season and all episodes?')) {
            seasonsData.splice(idx, 1);
            renderSeasons();
        }
    });

    $(document).on('click', '.add-episode-btn', function() {
        var idx = $(this).data('index');
        var nextEp = seasonsData[idx].episodes.length + 1;
        seasonsData[idx].episodes.push({ number: nextEp, title: 'Episode ' + nextEp, date: '', link: '' });
        renderSeasons();
    });

    // --- Bulk Link Importer ---
    $(document).on('click', '.bulk-link-btn', function() {
        var sIdx = $(this).data('index');
        var linksRaw = prompt("Paste your links here (one link per line):");
        
        if (linksRaw) {
            var linksArray = linksRaw.split(/\r?\n/).filter(line => line.trim() !== "");
            
            linksArray.forEach((url, i) => {
                if (seasonsData[sIdx].episodes[i]) {
                    seasonsData[sIdx].episodes[i].link = url.trim();
                }
            });
            
            renderSeasons();
            alert("Success! " + linksArray.length + " links applied to episodes.");
        }
    });

    // --- Auto Fetch Episodes (TMDB) ---
    $('#btn-fetch-episodes').on('click', function() {
        var tmdbId = $('#fetch_title').data('tmdb-id'); // We need to store this from the main fetch
        if (!tmdbId || !hoaData.tmdbKey) {
            // Try to find ID if not stored
            var title = $('#movie_title').val();
            if(!title) return alert('Please fetch the series details first.');
            alert('Please re-fetch the main series info using the TMDB button to capture the ID.');
            return;
        }

        var btn = $(this);
        btn.text('Fetching Seasons...').prop('disabled', true);
        seasonsData = []; // Clear existing

        // Get Main Details again to know season count
        $.getJSON(`https://api.themoviedb.org/3/tv/${tmdbId}?api_key=${hoaData.tmdbKey}`, function(details) {
            var totalSeasons = details.number_of_seasons;
            var seasonsProcessed = 0;

            if(totalSeasons === 0) {
                btn.text('No Seasons Found').prop('disabled', false);
                return;
            }

            // Loop through each season
            for (let i = 1; i <= totalSeasons; i++) {
                $.getJSON(`https://api.themoviedb.org/3/tv/${tmdbId}/season/${i}?api_key=${hoaData.tmdbKey}`, function(seasonData) {
                    var episodes = seasonData.episodes.map(ep => ({
                        number: ep.episode_number,
                        title: ep.name,
                        date: ep.air_date,
                        img: ep.still_path ? 'https://image.tmdb.org/t/p/w300' + ep.still_path : '',
                        link: '' // Empty for manual entry
                    }));

                    // Insert at correct index (async responses might be out of order)
                    seasonsData[seasonData.season_number - 1] = {
                        number: seasonData.season_number,
                        episodes: episodes
                    };

                    seasonsProcessed++;
                    if (seasonsProcessed === totalSeasons) {
                        // Filter out empty slots (specials often are season 0)
                        seasonsData = seasonsData.filter(n => n);
                        renderSeasons();
                        btn.text('Episodes Fetched!');
                    }
                });
            }
        });
    });

    // Capture TMDB ID when main fetch happens
    var originalFetchTMDB = fetchTMDB; // We need to hook into the existing function or modify it. 
    // Easier to just modify the existing fetchTMDB function above.
});
