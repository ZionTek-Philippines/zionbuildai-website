# ZionBuild AI — Interactive Demo ("Try it!") Plan

A static, guided demo of the ZionBuild app on the marketing site. Facebook ads link to the landing page; visitors click **Try it!** and explore a limited, walkthrough-guided copy of the real app with sample data. It is a sales tool, not the product, so it shows only the features worth highlighting.

---

## 1. Visitor Flow

1. Facebook ad → landing page (`/`).
2. Visitor clicks **Try it!** (hero + header).
3. A modal opens at **96% of the viewport** (rounded corners, dimmed backdrop, close ✕). It loads `/demo/how-it-works`.
4. The demo opens on **How It Works** (site → phone → laptop in 3 steps, ending with "Next: Command Center") with the **"Two ways to try ZionBuild" chooser**: a large panel (~70% of the screen) with two columns, each with a real screenshot of that mode: **Explore** (glowing dots, "Start exploring") and **Guide** (tour card, "Start the guided tour"). Closing it without choosing falls back to Explore (intro popup, then glowing dots). The coach mark shows every time someone enters the demo (Try it!, a refresh, or a direct link) and nothing is turned on until they choose. Moving between module pages keeps the chosen mode (Explore stays on; a running Guide continues on the next page). The Explore intro popup shows once per visit (sessionStorage). The walkthrough plays when the visitor presses **Guide**.
5. Visitor can pause the walkthrough, hover any section to see its explanation, or move to other demo modules.
6. Every screen keeps a **Book a Free Demo** CTA visible.

## 2. Routes (built one at a time, in this order)

| # | Route | Demo module | Reference pages in the real app (read-only) | Status |
|---|---|---|---|---|
| 0 | `/demo/how-it-works` | How It Works (first stop of Try it!) | Time-in (Employee + Selfie) and Time-out (Employee, photos/video, "Ano ginawa mo today?", Task, Work Date) | **Built** (`resources/views/demo/how-it-works.blade.php`, phone frame `components/demo/phone.blade.php`) |
| 1 | `/demo` | Command Center | `zpanel/{tenant}` Dashboard | **Built** (`resources/views/demo/command-center.blade.php`) |
| 2 | `/demo/field-reports` | Field Reports | `activity-page`, `activity-worker-page` (`camera-page` is a YouTube placeholder, not a feature, so it is left out) | **Built** (`resources/views/demo/field-reports.blade.php`) |
| 3 | `/demo/ai-project-monitoring` | AI Project Monitoring | `project-progress`, `accomplishment-report`, AI Photo Analysis, AI Progress Validation, AI Recommendations | **Built** (`resources/views/demo/ai-project-monitoring.blade.php`) |
| 4 | `/demo/finances` | Finances | `budgets`, expenses, bank accounts, transactions | **Built** (`resources/views/demo/finances.blade.php`) |
| 5 | `/demo/payroll` | Payroll | Worker Payrolls, Run Payroll, Payroll Runs, Cash Advances | **Built** (`resources/views/demo/payroll.blade.php`) |
| 6 | `/demo/client-billing` | Client Billing | Client Payments, homeowner panel | **Built** (`resources/views/demo/client-billing.blade.php`) |
| 7 | ~~`/demo/master-data`~~ | ~~Master Data~~ | — | **Dropped** (it reveals the data model and sells nothing; see §8) |

> **AI Project Monitoring:** progress, S-Curve, accomplishment reports and **AI Photo Analysis** are shipped and shown as working. **AI Progress Validation** and **AI Recommendations** are labeled **Beta** (see `ZIONBUILD_FEATURES.md` §4).

## 3. Workflow per Module

1. **Study the reference:** the real page in `../zionbuildai` (views, widgets, `app/Support/Guides/GuideCatalog.php` for walkthrough copy).
2. **Design in Google Stitch:** paste the module's prompt (section 7). Generate the main screen, then the walkthrough and hover states as follow-up prompts.
3. **Port to this site:** Blade view under `resources/views/demo/`, all data static, Tailwind tokens from `resources/css/app.css`.
4. **Add the walkthrough:** mark sections with `data-tour="…"` and list the steps (section 5).

### Implementation Map

| Piece | File |
|---|---|
| Demo shell (demo strip with module tabs, app top bar, sidebar) | `resources/views/components/demo/layout.blade.php` |
| Info badge that opens a section's explanation | `resources/views/components/demo/info.blade.php` |
| Walkthrough markup | `resources/views/demo/partials/guide.blade.php` |
| Walkthrough behavior (auto-play, hover tips, iframe links) | `resources/js/demo-guide.js` |
| "Try it!" 96% modal (landing page) | `resources/views/landing/try-it-modal.blade.php` + `initTryItModal()` in `resources/js/app.js` |
| Homepage hero screenshot | `public/images/hero-command-center.jpg`: `/demo?guide=off` at 1440×940 (scale 1.5) with the dark demo strip, chooser, launchers and Herd badge removed. Recapture when the Command Center changes. |
| Sample site photos (from Stitch, saved locally) | `public/images/demo/site-*.jpg`, `fr-*.jpg`, `hiw-*.jpg` (How It Works) |
| Chooser previews (real screenshots of this demo) | `public/images/demo/preview-explore.jpg`, `preview-guide.jpg`. Recapture at 1280×800 when the Command Center layout changes: Explore = `/demo?guide=off` with Explore on, Guide = `/demo?guide=5` (Net Cash Position step). Remove Herd's `#herd-ai-root` / `#herd-ai-host` badge before capturing. |
| Tests | `tests/Feature/DemoTest.php` |

Adding a module: create `resources/views/demo/<module>.blade.php` using `<x-demo.layout active="<key>">`, add the route, set its `route` in the layout's `$modules` list (this removes the "Soon" label), and give its sections `data-tour` + a `$tourSteps` list.

URL options: `?lang=tl` opens in Taglish; pages open with the Explore/Guide chooser by default; `?guide=1` auto-plays the tour from the start, `?guide=4` from step 4, and `?guide=off` opens with neither (useful for ads that point at a specific feature).

When porting Stitch output, strip anything it invents that isn't a confirmed feature. For the Command Center we removed: Export PDF, "Daily Biometrics", a bank brand name, OR numbers, receipt counts and fake ledger links.

## 4. Read-Only References — DO NOT EDIT

| Project | Path | Use it for |
|---|---|---|
| ZionBuild AI (the real app) | `/Users/iamreg/Joshua-Project/_ZionTekLabsAI/zionbuildai` | Layout, sections, labels and walkthrough copy (`app/Support/Guides/GuideCatalog.php`) |
| Ramcar SpendFlow | `/Users/iamreg/Joshua-Project/Kaisa/ramcarspendflow` | "User Guide" walkthrough design: `resources/views/components/user-guide.blade.php` |

Never modify, migrate, seed or run commands that write to these projects. Copy patterns into this repo instead.

## 5. Walkthrough ("User Guide") Behavior

Based on ramcarspendflow's `x-user-guide` (spotlight over `[data-tour]` sections, a step card with "Guide · n of N", Back / Next / End tour, arrow keys, Esc). The demo adds:

| Mode | Behavior |
|---|---|
| **Auto-play** | Starts when the visitor presses **Guide** (or the page is opened with `?guide=N`). Each step advances on its own after ~6s, with a progress bar on the card. Pauses when the visitor hovers the card or clicks Back/Next. A **Pause/Play** control on the card. |
| **"i" badge explain** | Sections no longer explain themselves on plain hover (it competed with Explore). Clicking or tapping a section's ⓘ badge outlines it and shows the step's text with **Start full guide → or explore on your own**. Tips opened from Explore dots show the explanation only. |
| **Explore mode** | **Explore** button beside **Guide** (bottom-right). Turns on a glowing dot at the corner of every walkthrough section; hover (desktop) or tap (phone) a dot to read that step's explanation while browsing freely. Button becomes **Done exploring**; Esc also exits. Starting the tour turns Explore off. The first time per page view, a centered popup over a blurred background explains Explore (glowing dots, click around freely, everything is sample data and nothing is saved, Guide is still available), with **Start exploring** / **Take the guided tour instead**. Dots have a pulsing glow and three staggered ripple rings (`animate-hotspot-glow`, `animate-hotspot-ripple` in `app.css`). |
| **Launcher** | Floating **Guide** pill (bottom-right) restarts the tour. |
| **Language** | **English / Taglish** toggle left of Explore (and top-left of the chooser). Switches all explanations: tour steps, Explore tips, chooser, Explore intro, Field Reports pop-up messages. The app screens stay in English like the real app; tour buttons (Next/Back) stay English, as in normal Taglish. Saved in localStorage; `?lang=tl` opens in Taglish (for Taglish ads). Blade copy uses `<x-demo.t en=".." tl=".." />`; tour steps carry `en` and `tl` blocks. |
| **Examples** | Most steps add an **Example / Halimbawa** box drawn from the sample data (e.g. "The client has paid ₱4,080,000 and you've spent ₱3,345,050, so the project is ₱734,950 ahead."). Shown in the tour card and Explore tips. |
| **End of tour** | The final card offers **Next module →** and **Book a Free Demo**. |

Walkthrough copy is benefit-first, short (1–3 sentences), written in both English and Taglish (the `en` / `tl` blocks in each page's `$tourSteps`), with a concrete example from the sample data where it helps. The scripts below show the English version. Explain only what is worth mentioning, not every number.

## 6. Demo Data Rules

- All data is **static sample data**, hard-coded in the views. No database, no calls to the real app.
- Use **fictional** project, client and worker names. Do not show real client projects or real staff names from the production app.
- The numbers must **add up** and show a **healthy project** (positive cash, within budget, on schedule).
- Currency is Philippine peso (₱), with `₱1,234,567` formatting.
- Follow `ZIONBUILD_FEATURES.md` §9 content rules (no invented claims; AI = coming soon; bank records ≠ bank integration).

### Command Center Sample Data (reconciled)

"Today" in the demo is **Sat, Oct 31, 2026** (Nov 1 is a Sunday and All Saints' Day, so no one would be on site).

| Item | Value | Derivation |
|---|---|---|
| Project | Ong Residential, Antipolo (2-storey) | fictional |
| Schedule | Jun 29, 2026 → Mar 31, 2027 | 124 days elapsed, **151 days remaining** |
| Contract Price | ₱10,200,000 | |
| Estimated Project Cost | ₱7,140,000 | budget (70% of contract) |
| Projected Earnings | ₱3,060,000 | contract − cost (**30%**, a typical contractor margin) |
| Total Gross Payroll | ₱1,148,600 | all payroll runs |
| Total Expenses | ₱2,196,450 | all paid expenses |
| Total Outflows | ₱3,345,050 | payroll + expenses |
| Utilized bar | ₱3,345,050 (**33%**) of contract price | |
| Total Inflow Collected | ₱4,080,000 | CP-0000001 ₱3,060,000 (30% downpayment, Jun 30) + CP-0000002 ₱1,020,000 (Progress Billing #1, Aug 28) |
| Net Cash Position | **₱734,950** | inflow − outflows |
| Realized to date | ₱734,950 (**24%** of projected earnings) | |
| Company Cash on Hand (Main Bank) | ₱2,486,730 | company-wide |
| Avg Daily Burn | ₱26,976 | 3,345,050 ÷ 124 days |
| Project Completion | **42%** | ~45% of schedule elapsed → on track |
| Workforce today | 14 (5 skilled, 9 laborers) | |

Recent payroll runs: PYR-0000023 Oct 25–31 ₱58,400 · PYR-0000022 Oct 18–24 ₱61,250 · PYR-0000021 Oct 11–17 ₱57,800 · PYR-0000020 Oct 4–10 ₱55,900 · PYR-0000019 Sep 27–Oct 3 ₱54,300.

Recent expenses: EXP-0000112 Oct 30 ₱186,500 (Ready-mix concrete 4,000 psi) · EXP-0000111 Oct 29 ₱48,720 (Deformed bars 12mm, Tie wire #16) · EXP-0000109 Oct 27 ₱15,120 (Coco lumber 2x2x10, Marine plywood ½") · EXP-0000108 Oct 24 ₱79,000 (Gravel, Sand) · EXP-0000105 Oct 21 ₱12,600 (Electrical pipe ½", Junction box).

Milestones (12 of 14 complete): M1 Jun 29 Site Clearing & Layout · M2 Jul 06 Footing Excavation · M3 Jul 13 Gravel Bedding · M4 Jul 20 Footing & Column Rebar · M5 Aug 01 Footing & Pedestal Pouring · M6 Aug 06 Foundation & Tie Beams · M7 Aug 11 Septic Tank · M8 Aug 18 Raised Ground-Floor Fill · M9 Aug 25 Ground-Floor Slab · M10 Sep 08 Ground-Floor Columns · M11 Sep 22 Ground-Floor CHB Walls · M12 Oct 13 Second-Floor Beams · **M13 Nov 07 Second-Floor Slab (in progress)** · M14 Mar 31 Roofing, Finishes & Turnover.

Active tasks: Second-floor slab rebar inspection (Structural · High · due Nov 3) · Order ready-mix concrete for slab pour (Procurement · High · due Nov 5) · Install second-floor shoring (Formworks · Medium · due Nov 6).

Latest Site Progress: Oct 31 *Today* Daily Work Log "Second-floor slab rebar tying, about 60% done." (Ruben Santos, Foreman) · Oct 30 Daily Work Log "Slab formworks installed on grids A–C." · Oct 28 Progress Update "Ground-floor CHB walls completed. Second-floor slab preparation started." (Engr. Paolo Reyes) · Oct 26 Daily Work Log "Scaffolding and shoring posts installed." · Oct 24 Daily Work Log "Gravel and sand delivery received and checked."

### Command Center Walkthrough Script

| # | `data-tour` | Title | Text |
|---|---|---|---|
| 0 | `module-menu` | Everything ZionBuild can do | Spotlights the module tabs in the demo strip and lists every module; SOON tabs are coming next. Shown as "Guide · 0 of 9" (an `overview` step keeps the page's own steps numbered 1–9). |
| 1 | — (centered) | Welcome to your Command Center | One screen for your whole project: money in and out, schedule, workforce and the latest site updates. Hindi mo na kailangang pumunta sa site para malaman ang nangyayari. |
| 2 | `project-switcher` | Switch projects | Managing several sites? Pick a project here and the whole dashboard follows it. |
| 3 | `financial-status` | Contract, budget & earnings | See your contract price, budget used, and projected earnings at a glance, so you know early if a project is getting tight. |
| 4 | `net-cash` | Net Cash Position | Money collected from the client minus money spent. Positive means the project is funding itself. |
| 5 | `money-cards` | Payments, payroll & expenses | The latest client payments, payroll runs and paid expenses, each with a running total. Every peso has a record. |
| 6 | `timeline` | Timeline & Cash Burn | Milestones, days remaining, daily burn and completion %. On schedule pa ba tayo? Makikita mo agad. |
| 7 | `workforce` | Who's on site today | Workers who timed in today, skilled vs laborers, straight from attendance. |
| 8 | `site-progress` | Latest Site Progress | Daily work logs and photos from the field. Actual progress, kita sa phone mo. |
| 9 | — (centered) | That's the Command Center | Next, see how your team sends Field Reports from the site. Buttons: **Next module →** · **Book a Free Demo** |

---

### Field Reports Sample Data

Mirrors the real `ActivityList` (cards: photos, description, performed by, type, client visibility, work date; actions: View, Analyze with AI, Show/Hide from client; Merge Updates for managers; worker form "Time-out + Photo of Accomplishment").

- **Daily Work Logs (8):** Oct 26–31 logs by Ruben Santos, Danilo Lim, Jessie Tan, Arnel Ocampo, Mateo Cruz. Today (Oct 31) has 3 logs with 7 + 3 + 2 = **12 photos**.
- **Progress Updates (4):** by Engr. Paolo Reyes: Oct 28, Oct 13, Sep 08 (shared) and Aug 18 (hidden).
- **Shared with client this week: 4** (3 work logs + the Oct 28 update). The count updates live when a visitor toggles Show/Hide.
- **Analyze with AI** is live (AI Photo Analysis is confirmed shipped; see `ZIONBUILD_FEATURES.md` §4).
- Interactive in the demo: tabs, Show/Hide from client, merge selection, the worker form (toasts explain what the real app does).
- The guide's spotlight passes clicks through to the highlighted element, so steps can say "click this button".

### AI Project Monitoring Sample Data

Four sub-tabs (`data-demo-tab` / `data-demo-panel`; tour steps with a `tab` key open their tab first):

- **Progress & S-Curve:** planned today **40.4%**, actual **42.0%**, variance **+1.6%**, 151 days left. Ten weighted phases (weights sum to 100); only Second-Floor Structure is in progress (46% planned, 57.1% actual). The S-Curve is drawn in Blade: planned = smoothstep fitted to 40.4% at week 18 and 100% at week 40; actual pulls ahead to 42.0% at week 18.
- **Accomplishment Report:** each phase's amount = weight × ₱10,200,000; accomplished totals **₱4,284,000** (42%), just ahead of the ₱4,080,000 collected.
- **AI Photo Analysis (live):** the Oct 31 rebar log (7 photos) with summary, confidence High, image quality Good, coverage Partial, limitations, and ranked matches (Slab reinforcement 45% → 60% recommended). "Accept match" updates the row and queue.
- **AI Insights (Beta):** AI Progress Validation (Approve / Modify / Reject) and two AI Recommendations (ready-mix booking, missing grid D–E photos).

Removed from Stitch's output: Export PDF, Refresh Analysis, "Eligible for Billing Claim #04", "AI Sync Live", server time, invented staff names, and the leftover merge bar.

### Finances Sample Data

Three sub-tabs. Budget = the ₱7,140,000 estimated project cost (70% of the contract, 30% margin).

- **Budget vs Actual:** 8 categories totaling ₱7,140,000. Spent ₱3,345,050 = Labor ₱1,148,600 (the payroll total) + paid expenses ₱2,196,450 (the Command Center's Total Expenses). Unpaid ₱64,250 (2 bills). Remaining after unpaid ₱3,730,700. **Deliberate story:** 47% of budget spent at 42% completion; Labor (49%) and Equipment & Hauling (94% incl. unpaid) are flagged "Watch", with an early-warning banner. Structural Materials at 74% is "On track" because the structure is mostly done.
- **Expenses:** 7 expenses (2 unpaid). Each row opens a drawer with line items that add up to the amount; unpaid ones can be "Marked as paid" (demo toast).
- **Bank & Cash:** Main Bank ₱2,486,730 (= Company Cash on Hand on the Command Center), Project Fund, Petty Cash, and a 6-row statement. Labeled "Recorded in ZionBuild by your team. Not connected to your bank." (features doc §9 rule 3).

Removed from Stitch's output: "BIR 2% Ret.", reconciliation labels, real bank brands, "Escrow", unpaid-queue / liquidity-float / QS-audit / audit-reconciliation panels, invented counts ("114 records", "18 workers") and receipt details (truck plate, batch no.).

### Payroll Sample Data

- **PYR-0000023 (Oct 25–31):** 14 workers (5 skilled at ₱900–₱1,000/day, 9 laborers at ₱600/day), 81 working days, gross **₱58,400** (= Command Center), vale ₱3,500, cash advance ₱2,000, net **₱52,900**. Each row's "Payslip" opens a drawer with Mon–Sat attendance and the deductions. Finances' bank statement shows the payroll payment as the net ₱52,900 (vale and cash advances leave the bank when given).
- **Vale & Cash Advances:** kept short on purpose (per the product owner): one compact table, outstanding balance ₱7,500, no explainer.
- **Payroll Runs:** the 5 runs from the Command Center payroll card (₱287,650 gross).
- Removed from Stitch's output: "Disburse via BDO", "Disbursement Engine", "BIR 2% / DOLE compliant", "geo-tagged roll call", "biometric", "Envelope cashout ready", export/bulk-print buttons. No worker self-service payslip (Worker Payrolls is owner-only in the real app).

### How It Works Sample Data

- **Step 1 Time in:** Ruben Santos (foreman) times in at 7:02 with a selfie. The selfie is a crop of `hiw-01.jpg`.
- **Step 2 Time out:** 5:04 PM, 7 photos, "Second-floor slab rebar tying, about 60% done.", Oct 31, 2026. This is the same work log as Field Reports and the AI Photo Analysis queue.
- **Step 3 Laptop:** Field Reports with 14 workers timed in, 3 work logs and 12 photos (matching the Field Reports today strip). Ruben's log is marked NEW.
- Devices are drawn in HTML at real proportions (phone 9 : 19.5, laptop 16:10 screen plus base), not taken from Stitch.
- Removed from Stitch's output: GPS / face verification / geo-stamps, "telemetry", "Sunrise Roll Call", "Live sync", "100% Paperless", "No paper logbooks", "Verified" badges, "Logs straight to worker daily register".

### Client Billing Sample Data

- **Client Payments:** CP-0000001 ₱3,060,000 (30% Downpayment, check, Jun 30) and CP-0000002 ₱1,020,000 (Progress Billing #1, bank transfer, Aug 28) are Received = ₱4,080,000 (the Command Center's Total Inflow, 40%). CP-0000003 ₱1,020,000 (Progress Billing #2, check, Oct 31) is **Pending** and not counted. Work done ₱4,284,000 (Accomplishment Report) is ₱204,000 ahead of collections.
- **Client Portal:** a phone mock of the homeowner view: 42% complete, next milestone Nov 7, only the updates marked "Show to client", payments, and the Accomplishment Report. "Your costs, budget, expenses and payroll are never shown to the client."
- Removed from Stitch's output: "Export BIR Form 2307", "Philippine Contractor Lien Safe", "certified by site architect", portal link / PIN / "last viewed" tracking, OR batch download, footer claims. The portal shows Accomplishment Reports instead of a documents list (features doc §3, #14).

## 7. Google Stitch Prompts

How to use: open stitch.withgoogle.com → **Web** → paste **Prompt A** → generate. Then send **B** and **C** as follow-up edits to the same screen so they stay consistent. Export the HTML/Tailwind and port it into Blade.

### 7.1 Command Center

**Prompt A: main screen**

```text
Design a desktop web app dashboard screen (1440px wide, full-page scroll) called "Command Center" for ZionBuild, a construction project management SaaS for Philippine contractors. This is a guided demo version of the real app, so it uses static sample data.

STYLE
- Clean, premium SaaS. Fonts: Manrope (headings, big numbers, bold, tight tracking) and Inter (body, labels).
- Page background: very soft gradient from #f6fafe (left) to light lavender #ece6fb (right).
- Primary dark navy: #182338. Success green: #006c49 with mint #6cf8bb. Warning/outflow accent: soft orange #f6b48f. Accent blue for links: #3b5bdb.
- Cards: white, 16px radius, subtle shadow, 24px padding. Small labels are 11px uppercase with letter-spacing, gray.
- Peso amounts use the ₱ sign with comma separators.

DEMO FRAME (very top, above the app)
- A thin 40px strip, dark navy #182338: left "● DEMO MODE · Sample data only" in mint; center a row of pill tabs: Command Center (active, white), Field Reports, AI Project Monitoring, Finances, Payroll, Client Billing, Master Data; right a "Book a Free Demo" pill button with a blue-to-violet gradient, and a ✕ close icon.

APP TOP BAR (64px)
- Background: horizontal gradient from deep indigo #24124f to violet #6d3fd0.
- Left: white "zionbuild" wordmark logo; then a translucent pill project switcher "🏠 Ong Residential ⇄".
- Right: bell icon with badge "1", then 4 round colored icon buttons (blue team, red camera, green copy, light "+"), then a black avatar circle "D".

LEFT SIDEBAR (72px, white, icons only, Material Symbols Outlined, gray)
- Company badge "ZB" at top in black rounded square. Icons stacked with spacing: home (active, light blue background), bolt, rocket, videocam, divider, bar_chart, receipt, flag, photo_camera, menu_book, divider, account_balance_wallet, payments, account_balance, swap_horiz, divider, groups, list, receipt_long.

MAIN CONTENT (max width 1200px, centered)
Page title "Command Center" (Manrope 28px bold).

ROW 1 (two cards, 2/3 and 1/3 width):
1) "Financial Status" card with a soft pink-to-periwinkle gradient background (#fce7f3 → #c7d2fe).
   - Top left: project name "Ong Residential" (Manrope 30px extra bold) with a small eye icon in a circle; below "Jun 29, 2026 — Mar 31, 2027".
   - Top right: small label "CONTRACT PRICE", big "₱10,200,000".
   - Bottom two columns:
     a) "ESTIMATED PROJECT COST" ₱7,140,000, an orange progress bar at 33%, italic note "Utilized ₱3,345,050 (33%) of contract price".
     b) "PROJECTED EARNINGS" ₱3,060,000 in green, a green progress bar at 24%, italic note "Realized ₱734,950 to date".
2) "Net Cash Position" card, dark navy #182338 with a faint large compass/drafting-tool watermark at bottom right.
   - Label "NET CASH POSITION", huge white number "₱734,950".
   - Rows separated by thin lines: "Total Inflow Collected" "+ ₱4,080,000" (mint green); "Total Outflows" "− ₱3,345,050" (soft orange); "Company Cash on Hand (Main Bank)" "₱2,486,730" (white).

ROW 2 (three equal cards, each with a dark navy header bar 48px, icon + white title, and a small status chip on the right):
1) "Client Payments", chip "INFLOW" (mint bg, green text). Body: label "TOTAL COLLECTED", big "₱4,080,000". List rows with a green check circle: "CP-0000002 · Progress Billing #1 · Aug 28, 2026 · ₱1,020,000"; "CP-0000001 · 30% Downpayment · Jun 30, 2026 · ₱3,060,000".
2) "Workers Payroll", chip "OUTFLOW" (peach bg, orange text). Body: "TOTAL GROSS PAYROLL" "₱1,148,600". Five rows with a receipt icon tile: PYR-0000023 Oct 25–Oct 31 ₱58,400; PYR-0000022 Oct 18–Oct 24 ₱61,250; PYR-0000021 Oct 11–Oct 17 ₱57,800; PYR-0000020 Oct 04–Oct 10 ₱55,900; PYR-0000019 Sep 27–Oct 03 ₱54,300.
3) "Expenses", chip "OUTFLOW". Body: "TOTAL EXPENSES" "₱2,196,450". Five light-gray rounded rows, each with a bold reference and date on the left, amount on the right, and a blue materials line under it: EXP-0000112 · Oct 30, 2026 · ₱186,500 · Ready-mix Concrete 4,000 psi; EXP-0000111 · Oct 29 · ₱48,720 · Deformed Bars 12mm, Tie Wire #16; EXP-0000109 · Oct 27 · ₱15,120 · Coco Lumber 2x2x10, Marine Plywood ½"; EXP-0000108 · Oct 24 · ₱79,000 · Gravel, Sand; EXP-0000105 · Oct 21 · ₱12,600 · Electrical Pipe ½", Junction Box.

ROW 3 (full width) "Timeline & Cash Burn" card, light gray-lavender background, thick dark navy left border.
- Title "Timeline & Cash Burn", subtitle "150 days remaining in project cycle".
- Top right two white stat boxes: "AVG DAILY BURN ₱26,760" and "PROJECT COMPLETION 42%" (green), plus a small green pill "On track".
- A horizontal milestone line with 14 dots, labels alternating above and below (date bold, name small gray). Dots 1–12 are filled dark navy with a white check; dot 13 is a pulsing blue ring labeled "NOV 07 · Milestone 13 — Second-Floor Slab · In progress"; dot 14 is an empty gray ring. Milestones: JUN 29 Site Clearing & Layout; JUL 06 Footing Excavation; JUL 13 Gravel Bedding; JUL 20 Footing & Column Rebar; AUG 01 Footing & Pedestal Pouring; AUG 06 Foundation & Tie Beams; AUG 11 Septic Tank; AUG 18 Raised Ground-Floor Fill; AUG 25 Ground-Floor Slab; SEP 08 Ground-Floor Columns; SEP 22 Ground-Floor CHB Walls; OCT 13 Second-Floor Beams; NOV 07 Second-Floor Slab; MAR 31 Roofing, Finishes & Turnover.

ROW 4 (two columns, 45% / 55%):
Left column:
- "Total Workforce" card with dark navy header and a worker icon. Body: huge "14" and "Workers on Site Today"; two small pills "5 Skilled" and "9 Laborers"; a row of 6 overlapping initials avatars plus "+8".
- "Active Tasks" card with "View All →" link. Three task rows, each with a title, a category chip, a priority chip, due date and assignee initials: "Second-floor slab rebar inspection" (Structural, High, Due Nov 3, PR); "Order ready-mix concrete for slab pour" (Procurement, High, Due Nov 5, RS); "Install second-floor shoring" (Formworks, Medium, Due Nov 6, RS).
Right column: "Latest Site Progress" card, light background, vertical timeline with gray dots on the left. Five entries, each with a header line in small uppercase "OCT 31, 2026 • TODAY • Daily Work Log" (the today entry has a mint "Today" highlight), then a white rounded card with one large construction photo on the left and three small thumbnails on the right, a one-line description, and an initials avatar with name:
  1) Oct 31 Today, Daily Work Log, "Second-floor slab rebar tying, about 60% done.", RS Ruben Santos
  2) Oct 30, 1 day ago, Daily Work Log, "Slab formworks installed on grids A–C.", RS Ruben Santos
  3) Oct 28, 3 days ago, Progress Update, "Ground-floor CHB walls completed. Second-floor slab preparation started.", PR Engr. Paolo Reyes
  4) Oct 26, 5 days ago, Daily Work Log, "Scaffolding and shoring posts installed.", RS Ruben Santos
  5) Oct 24, 1 week ago, Daily Work Log, "Gravel and sand delivery received and checked.", RS Ruben Santos
  Photos: realistic Philippine residential construction sites: concrete hollow block walls, rebar, wooden formworks, bamboo/steel scaffolding, gray skies.

FOOTER: centered tiny gray uppercase "ZIONBUILD © 2026 · CRAFTED BY ZIONTEK IT SOLUTIONS LABS".

FLOATING: bottom-right pill button "💡 Guide" in blue #3b5bdb with white text and shadow.

Every card has a tiny circular "i" info badge in its top-right corner (light gray), used for hover explanations.
```

**Prompt B: walkthrough step (follow-up edit)**

```text
Create a second version of this same Command Center screen showing the guided walkthrough in progress:
- Dim the whole page with a white 70% overlay EXCEPT the "Net Cash Position" card, which stays fully visible with a 2px blue #3b5bdb outline, 8px offset, and 16px radius (spotlight effect).
- Below the spotlighted card, place a white walkthrough card (352px wide, 16px radius, large soft shadow, 24px padding):
  - Tiny bold uppercase blue label "GUIDE · 4 OF 9" with a small "▶ Auto-playing" chip on the right.
  - Title (Manrope 16px bold): "Net Cash Position".
  - Body (Inter 14px gray): "Money collected from the client minus money spent. Positive means the project is funding itself."
  - A thin blue progress bar under the body, 60% filled (auto-advance timer).
  - Footer row: left "End tour" gray underlined text; right a small round pause button, a gray "Back" button and a blue "Next" button (10px radius).
- Hide the floating "Guide" pill while the tour is active.
```

**Prompt C: hover explanation (follow-up edit)**

```text
Create a third version of the Command Center screen with no tour running, showing the hover explanation state:
- The mouse cursor is over the "Timeline & Cash Burn" card. That card has a 2px blue #3b5bdb outline and its "i" badge is filled blue.
- A small dark navy #182338 tooltip (300px wide, 12px radius, white text, small arrow pointing to the card) floats above the card's top-right: bold title "Timeline & Cash Burn" and text "Milestones, days remaining, daily burn and completion %. On schedule pa ba tayo? Makikita mo agad." and a small mint link "Start full guide →".
- The rest of the page stays normal (not dimmed). The floating "Guide" pill is visible.
```

### 7.2 Field Reports

Prompts used (pasted after the Command Center screens, so Stitch kept the same shell):

- **A, desktop list:** the shell with Field Reports active; a "today" strip (timed in, work logs, photos, shared with client); Daily Work Logs / Progress Updates tabs with filters and a red "New Photo of Accomplishment" button; a 3-column card grid (photo, description, performed-by badge, type badge, client visibility badge, work date, View / Analyze with AI "Soon" / Show-Hide client actions).
- **B, phone form:** a 390px bottom sheet "Time-out + Photo of Accomplishment" with Employee, "Photos / Video ng Work Accomplishment" upload plus thumbnails, "Ano ginawa mo today?", Task (optional), Work Date, and a red Submit.
- **C, manager view:** the Progress Updates tab with merge checkboxes, a floating "2 selected · Merge Updates · Clear" bar, and a "You decide what the client sees" tooltip on the Show/Hide button.

When porting we removed Stitch's "Live Sync: Active" chip, the breadcrumb and the pagination, and replaced the mismatched septic-tank photo with an Aug 18 ground-fill update.

### 7.3 AI Project Monitoring

Prompts used: **A** Progress & S-Curve with the phase table and an Accomplishment Report preview; **B** AI Photo Analysis (analysis queue + selected analysis with summary, stats, limitations and the milestone assessment); **C** AI Insights (Beta) with AI Progress Validation and AI Recommendations. In the build, the Accomplishment Report became its own tab with all ten items.

### 7.4 Finances

Prompts used: **A** Budget vs Actual (KPIs, health banner, category table, paired-bar chart); **B** Expenses (filters, paid/unpaid chips, table, detail drawer); **C** Bank & Cash (not-connected note, 3 accounts, Main Bank statement).

### 7.5 Payroll

Prompt used: **A** This Week's Payroll (run header, 5 KPIs, 14-worker register). The Vale & Cash Advances and Payroll Runs tabs were built directly from the prompt B/C specs, simplified.

### 7.0 How It Works on Site

Prompt used: three step cards (realistic photo left, dotted connector, zoomed device right) plus the "Next: Command Center" CTA. Only the three photos were kept; the phones and laptop were rebuilt in HTML.

### 7.6 Client Billing

Prompts used: **A** Client Payments (KPIs, work-done-vs-collected bar, client card, payments table); **B** Client Portal (what the client sees + homeowner phone mock).

## 8. Protecting the Product (public demo rules)

Decided Nov 2026, so competitors learn as little as possible from the public demo:

1. **Six modules only.** Master Data is not built: it exposes the internal setup/data model and sells nothing.
2. **Beta AI is a teaser.** The AI Insights tab says "More AI is on the way" with a "book a demo" link; no AI Progress Validation table or Recommendations cards on the public demo.
3. **Gated modules.** Finances, Payroll and Client Billing sit behind a short "Unlock the full demo" form (name, PH mobile, company/project, role). The gate is server-side (`App\Http\Middleware\EnsureDemoIsUnlocked`, which shares `$isDemoLocked` with the page): a locked page renders only its top (title, tabs and KPI tiles) as a teaser, with the form (`demo/partials/unlock-form.blade.php`) over a blurred skeleton below it. The other tabs are disabled and everything below the KPI tiles is never rendered, so it is not in the page source either. Unlocking is per session. Leads are saved to `demo_leads` (`App\Models\DemoLead`) and listed read-only in the `zpanel` admin under **Demo Leads**. Bots are filtered by a honeypot field and a 10-per-minute rate limit.
4. **Explain outcomes, never mechanisms.** Tour and Explore copy say what a feature does for the contractor, not how it works (no AI prompts, scoring rules or formulas).
5. **Sample data only**, and the demo stays `noindex`.
