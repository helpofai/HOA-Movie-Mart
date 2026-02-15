# ⚙️ HOA Movie Mart Core Plugin

The backbone of the HOA Movie Mart ecosystem. This plugin handles the data architecture, custom post types, and backend logic required for the theme to function at a professional level.

---

## 🚀 Key Functionality

### 📂 Custom Post Types (CPT)
- **Movies (`movie`):** The primary content type for movies and TV series.
- **Reports (`report`):** Automates the dead-link reporting system.

### 🏷️ Custom Taxonomies
- **Genres:** Dynamic movie classification.
- **Years:** Temporal organization.
- **Quality:** Filtering by resolution (4K, 1080p, etc.).
- **Studios/Networks:** Brand-based filtering.

### 💾 Metadata Management
- **Movie Details:** IMDb Rating, Runtime, Trailer URLs, and Cast.
- **Download Engine:** Secure JSON-based storage for multi-server download links.
- **Stats Tracker:** Built-in view and download counter for "Trending" logic.

---

## 🔌 Integration

### Theme Dependency
This plugin is specifically designed to work with the **HOA Movie Mart Theme**. It provides the necessary data fields (Meta Boxes) that the theme displays on the front end.

### AJAX Handlers
- `hoa_live_search`: Powers the cinematic search overlay.
- `hoa_report_dead_link`: Processes user-submitted link error reports.
- `hoa_verify_gate`: Manages the human-verification sequence for downloads.

---

## 🛠️ Setup
1. Upload to `/wp-content/plugins/hoa-movie-mart-core/`.
2. Activate via the WordPress Plugins dashboard.
3. Configure **Turnstile Site Key** in the Theme Settings for secure download links.

---

## 🛡️ Security
- **Nonce Verification:** All AJAX requests are protected with security nonces.
- **Data Sanitization:** Strict use of `sanitize_text_field` and `absint` for all metadata updates.
- **Bot Protection:** Integrated logic to prevent automated scrapers from stealing download links.

---

## 📜 License
Developed by the HelpOfAi team. Intended for use exclusively with HOA Premium Themes.
