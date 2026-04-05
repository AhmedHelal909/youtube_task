# 🎬 YouTube Educational Course Scraper

A Laravel-based web application that discovers educational YouTube playlists (courses) using **AI-generated search queries** (Google Gemini) and displays them in a clean, RTL Arabic UI.

---

## 📸 Preview

The UI matches the provided design:
- **Dark navy hero section** with a category-input textarea and AI fetch trigger
- **Red-accented filter tabs** per category
- **Course card grid** showing thumbnail, title, channel, and video count
- **Arabic RTL layout** using Cairo font + Bootstrap 5 RTL

🎥 **Demo:** [see Demo Here](https://drive.google.com/file/d/1DjnFY8L_-Y1rhY0lkHEwL2DCG2ff_IFF/view?usp=sharing)

---

## 🏗 Architecture Overview

```
User Input (categories)
       │
       ▼
CourseController::fetch()
       │
       ▼
CourseFetcherService::run()
       │
       ├──► GeminiService::generateCourseTitles(category)
       │           └── Calls Gemini API → returns 10 search queries
       │
       └──► YouTubeService::searchPlaylists(query, limit=2)
                   ├── search.list  → finds 2 playlists per query
                   ├── playlists.list → fetches video counts
                   └── Course::firstOrCreate() → deduplication by playlist_id
```

---

## ⚙️ Requirements

| Tool       | Version  |
|------------|----------|
| PHP        | ≥ 8.1    |
| Composer   | ≥ 2.x    |
| MySQL      | ≥ 8.0    |
| Node.js    | ≥ 18 (optional, only if you add Vite assets) |

---

## 🚀 Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/your-username/youtube-scraper.git
cd youtube-scraper
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Copy and configure environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure API keys

Open `.env` and fill in:

```dotenv
# ── Database ──────────────────────────────────────────────────
DB_DATABASE=youtube_scraper
DB_USERNAME=root
DB_PASSWORD=your_password

# ── Google Gemini API ─────────────────────────────────────────
# Get yours at: https://aistudio.google.com/
GEMINI_API_KEY=AIza...

# ── YouTube Data API v3 ────────────────────────────────────────
# Get yours at: https://console.cloud.google.com/
# Enable: YouTube Data API v3
YOUTUBE_API_KEY=AIza...
```

### 5. Create the database

```bash
mysql -u root -p -e "CREATE DATABASE youtube_scraper CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. (Optional) Seed demo data

To view the UI with sample courses without making real API calls:

```bash
php artisan db:seed --class=DemoSeeder
```

### 8. Start the development server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 API Keys Configuration

### Google Gemini API
1. Go to [https://aistudio.google.com/](https://aistudio.google.com/)
2. Click **Get API Key**
3. Create a new key and copy it to `GEMINI_API_KEY`

> **Model used:** `gemini-2.5-flash` — fast and cost-effective for batch generation

### YouTube Data API v3
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create or select a project
3. Enable **YouTube Data API v3** under *APIs & Services → Library*
4. Create credentials → **API Key**
5. Copy the key to `YOUTUBE_API_KEY`

> **Quota note:** Each search costs 100 units. With 10 queries × N categories, budget accordingly. Default daily quota is 10,000 units.

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   └── CourseController.php       # Handles index, fetch, job-log routes
├── Models/
│   ├── Course.php                 # Eloquent model with deduplication scope
│   └── FetchJob.php               # Tracks each fetch session + log
├── Services/
│   ├── GeminiService.php          # Calls Gemini API → generates search queries
│   ├── YouTubeService.php         # Calls YouTube API → finds playlists
│   └── CourseFetcherService.php   # Orchestrates the full pipeline
├── Providers/
│   └── AppServiceProvider.php     # Registers services as singletons

database/
├── migrations/
│   ├── ..._create_courses_table.php
│   └── ..._create_fetch_jobs_table.php
└── seeders/
    └── DemoSeeder.php             # 12 demo courses for UI preview

resources/views/
├── layouts/app.blade.php          # Master layout (navbar, flash, footer)
└── courses/
    ├── index.blade.php            # Home page (input form + course grid)
    ├── _card.blade.php            # Single course card partial
    ├── _pagination.blade.php      # Custom RTL pagination
    └── job.blade.php              # Fetch-job log viewer

public/
└── css/app.css                    # Custom CSS (matches the design)

routes/
├── web.php                        # GET / | POST /fetch | GET /jobs/{id}
└── api.php                        # GET /api/courses (JSON)
```

---

## 🗃 Database Schema

### `courses`

| Column        | Type         | Notes                          |
|---------------|--------------|--------------------------------|
| `id`          | bigint PK    | Auto-increment                 |
| `playlist_id` | varchar UNIQUE | YouTube playlist ID — **deduplication key** |
| `title`       | varchar      |                                |
| `description` | text NULL    |                                |
| `thumbnail_url` | varchar NULL |                              |
| `channel_name` | varchar NULL |                               |
| `category`    | varchar      | Indexed for fast tab filtering |
| `video_count` | int unsigned |                                |
| `playlist_url` | varchar NULL |                               |
| `created_at`  | timestamp    |                                |
| `updated_at`  | timestamp    |                                |

### `fetch_jobs`

| Column          | Type      | Notes                              |
|-----------------|-----------|------------------------------------|
| `id`            | bigint PK |                                    |
| `categories`    | json      | Array of submitted categories      |
| `status`        | varchar   | pending / running / completed / failed |
| `total_saved`   | int       | New playlists inserted             |
| `total_skipped` | int       | Duplicates found and skipped       |
| `total_errors`  | int       | API/network errors                 |
| `log`           | json NULL | Array of timestamped log lines     |
| `started_at`    | timestamp |                                    |
| `finished_at`   | timestamp |                                    |

---

## 🔄 Deduplication Logic

Deduplication is enforced at the **database level** via a `UNIQUE` constraint on `playlist_id` (the YouTube playlist ID), and at the **application level** via:

```php
// CourseFetcherService.php
Course::where('playlist_id', $data['playlist_id'])->first();
// → only creates if not found
```

This two-layer approach means:
- Even concurrent requests cannot insert duplicate rows (DB constraint)
- The application knows whether a playlist was new or skipped (counts tracked in FetchJob)

---

## 🌐 Routes

| Method | URL            | Description                        |
|--------|----------------|------------------------------------|
| GET    | `/`            | Home page — input form + grid      |
| POST   | `/fetch`       | Start AI + YouTube pipeline        |
| GET    | `/jobs/{id}`   | View fetch-job log                 |
| GET    | `/api/courses` | JSON API — paginated courses list  |

---

## 💡 Design Decisions

1. **Synchronous pipeline** — `QUEUE_CONNECTION=sync` runs the pipeline in the same HTTP request. For production, convert `CourseFetcherService::run()` into a queued `Job` and dispatch it. Add a polling endpoint to stream status to the frontend.

2. **Gemini 2.5 Flash** — Chosen for speed and cost. Upgrade to `gemini-2.5-pro` for higher-quality title generation if needed.

3. **2 playlists per query** — Matches the spec and conserves YouTube API quota (100 units/search × 10 queries × N categories).

4. **FetchJob log** — Every step (saved, skipped, error) is written to `fetch_jobs.log` as a JSON array. View it at `/jobs/{id}`.

---

## 🧪 Testing the APIs manually

```bash
# Test Gemini
curl "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"contents":[{"parts":[{"text":"Say hello"}]}]}'

# Test YouTube
curl "https://www.googleapis.com/youtube/v3/search?part=snippet&q=python+course&type=playlist&maxResults=2&key=$YOUTUBE_API_KEY"
```

---

## 📄 License

MIT