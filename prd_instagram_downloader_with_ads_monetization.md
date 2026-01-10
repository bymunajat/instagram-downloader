# Product Requirements Document (PRD)

## Product Name
Instagram Downloader (Powered by Cobalt API)

## Product Overview
Instagram Downloader is a web-based application that allows users to download public Instagram content such as posts, reels, videos, images, and carousel media. The application is designed to be simple for non-technical users while providing monetization opportunities through in-app advertisements.

The product consists of:
- A **public frontend** for users to paste Instagram links and download media
- An **admin dashboard** to manage ads, basic settings, and usage statistics

This PRD focuses on an MVP suitable for demo purposes and early monetization.

---

## Goals & Objectives

### Primary Goals
- Allow users to download public Instagram media easily
- Provide a clean, modern, and responsive UI
- Enable ad-based monetization without disrupting user experience

### Secondary Goals
- Simple admin control without complex backend management
- Easy deployment for demo and production environments

---

## Target Users

### End Users
- General users who want to download Instagram videos, reels, images, or carousels
- Non-technical users

### Admin Users
- Site owner / operator
- Non-technical or semi-technical users

---

## Core Features (Public Frontend)

### 1. Media Download
- Input field for Instagram URL
- Supported content:
  - Single image post
  - Video post
  - Reels
  - Carousel (mixed image/video)
- Preview before download
- Individual download button per media item

### 2. Multi-language Support
- Language selector (initially English & Indonesian)
- Easy to extend to additional languages

### 3. Responsive UI
- Optimized for desktop and mobile
- Dark mode design (default)

### 4. Informational Pages
- Home
- How to Use
- About

---

## Monetization Features (Ads-Focused)

### Ad Types
- Google AdSense
- Custom HTML / script-based ads

### Ad Placement (MVP)
- Header banner
- Below download form
- Between carousel results
- Footer banner

Ads must:
- Not block core functionality
- Load asynchronously

---

## Admin Dashboard (MVP)

### Dashboard Goals
- Simple ad management
- No complex user management
- Focused on monetization and basic configuration

### Admin Pages & Features

#### 1. Dashboard Overview
- Total downloads (daily / total)
- Active ads count
- System status (Frontend / API)

#### 2. Ads Management
- Enable / disable ads globally
- Manage ad slots:
  - Header Ad
  - Content Ad
  - Footer Ad
- Input fields for:
  - Google AdSense code
  - Custom HTML/JS ads

#### 3. Appearance Settings
- Site name
- Primary accent color
- Logo upload (optional)

#### 4. Language Settings
- Enable / disable available languages

#### 5. System Settings
- Cobalt API endpoint URL
- API status indicator (online/offline)

---

## Non-Goals (Out of Scope for MVP)
- User authentication / accounts
- Payment systems
- Subscription-based monetization
- Private Instagram content

---

## Technical Architecture

### Frontend
- HTML
- Tailwind CSS
- Vanilla JavaScript

### Backend
- PHP (for proxying download requests)
- Cobalt API (running on VPS for production)

### Deployment
- Frontend: Vercel or static hosting (demo)
- Full system: VPS (Frontend + Cobalt API)

---

## Security & Compliance
- Only public Instagram content supported
- No user data storage
- No login or personal data collection

---

## MVP Success Criteria
- Users can successfully download media
- Ads are displayed correctly and configurable via admin
- Admin dashboard usable by non-technical users
- Stable demo environment for client presentation

---

## Future Enhancements (Post-MVP)
- Analytics dashboard (CTR, ad impressions)
- More languages
- Theme switcher (light / dark)
- CDN optimization
- Rate limiting & abuse protection

---

## Summary
This MVP focuses on a **simple, ad-monetized Instagram Downloader** with a clean frontend and a lightweight admin dashboard. The product is designed to be easy to demo, easy to operate, and scalable for future monetization improvements.

