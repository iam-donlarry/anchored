# De AnchoredSelf, LLC — Public Website & Practice CMS

**Tagline:** *Grounded in Healing. Growing in Strength. Delivering Hope.*  
**Location:** 403 Eastern Boulevard, Essex, Maryland 21221  
**Telephone:** 443-912-4612  
**Hours:** Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time (Telehealth & In-Person)

---

## 1. Architecture Overview

### Code-Defined Public Website Pages
All core public website pages, navigation menus, layouts, service descriptions, and FAQs are **code-defined directly in the frontend PHP files** and do not depend on database page/service records to render:
- **Home** (`index.php`) — Code-defined hero, populations served, care pillars, "Our Approach" section, and selected services overview, with optional dynamic sections for published CMS Videos/Resources and Upcoming Events.
- **About Our Practice** (`about/index.php`) — Code-defined practice introduction, Mission/Vision/Values triad, and "Our Approach & Provider Experience" section (`Dr. Emmanuella Owobu, PhD, DFM, LCPC-S, NCC, CCMHC, CAC-AD`).
- **Explore Our Services** (`services/index.php`) — Code-defined directory of all psychotherapy, faith-integrated, wellness, assessment consultation, and clinical supervision services.
- **Psychotherapy** (`psychotherapy/index.php`) — Code-defined individual, couples, family, group, child & adolescent (ages 5+), "Just a Place to Talk", trauma-informed, and substance-use counseling sections.
- **Christian Counseling** (`christian-counseling/index.php`) — Code-defined optional faith-integrated counseling overview and pillars.
- **Wellness** (`wellness/index.php`) — Code-defined whole-person wellness, behavioral pain-management counseling, herbalism, juice therapy, weight-management support, and weight-loss surgery assessment consultation sections.
- **Weight-Loss Surgery Assessments** (`weight-loss-surgery-assessments/index.php`) — Code-defined pre-surgical behavioral-health consultation and pain management program eligibility assessment page.
- **Clinical Supervision** (`clinical-supervision/index.php`) — Code-defined counselor supervision and professional development page.
- **Membership and FAQ** (`membership-faq/index.php`) — Code-defined appointments/location, unpublished fees & membership overview, 72-hour cancellation/nonrefund policy, and 12-question accessible FAQ accordion.
- **Videos and Resources** (`resources/index.php`) — Code-defined layout displaying published CMS-managed educational resources (`videos_resources`).
- **Events and Gallery** (`events/index.php`) — Code-defined layout displaying published CMS-managed upcoming events, past events, and past-event gallery albums (`events`, `gallery_albums`, `gallery_media`).
- **Contact & Appointment Inquiry** (`contact/index.php`) — Code-defined contact details and routine appointment inquiry form (`inquiries`).
- **Privacy Information & Practice Policies** (`privacy-policy/index.php`) — Code-defined routine website inquiry notices, emergency disclaimer, and cancellation policy.

### Focused Practice CMS (`/cms/`)
The CMS is strictly scoped to content that the practice updates regularly without a developer:
1. **Dashboard** (`/cms/dashboard.php`) — Overview of new/reviewed appointment inquiries, upcoming events, published videos/resources, gallery albums, and recent administrative activity.
2. **Events** (`/cms/modules/events/`) — Create, edit, publish/unpublish upcoming events, and move completed events to Past Events & Gallery.
3. **Videos & Resources** (`/cms/modules/videos/`) — Add and manage educational videos, thumbnails, captions, transcripts, categories, and page placements.
4. **Event Gallery** (`/cms/modules/gallery/`) & **Media Library** (`/cms/modules/media/`) — Manage past-event photo/video albums and shared media uploads.
5. **Appointment Inquiries** (`/cms/modules/inquiries/`) — Review, filter, add internal notes, archive, or delete routine inquiries submitted through the public Contact page.
6. **Settings** (`/cms/modules/settings/`) — Update global practice contact details, hours, and inquiry confirmation notices.

---

## 2. Database Schema & Seeding

The MySQL database (`anchored`) contains 10 tables focused exclusively on CMS-managed entities:
- `cms_admins` & `cms_login_attempts`
- `events`
- `videos_resources`
- `gallery_albums` & `gallery_media`
- `media_library`
- `inquiries`
- `site_settings`
- `audit_logs`

### Re-applying Schema & Seed Data
```powershell
Get-Content "c:\xampp\htdocs\anchored\database\schema.sql" -Raw | C:\xampp\mysql\bin\mysql.exe -u root anchored
C:\xampp\php\php.exe "c:\xampp\htdocs\anchored\database\seed.php"
```

### Provisioning an Administrator (CLI Only)
```powershell
C:\xampp\php\php.exe "c:\xampp\htdocs\anchored\cms\provision-admin.php" --username=admin --email=admin@deanchoredselfs.org --name="Practice Administrator"
```
