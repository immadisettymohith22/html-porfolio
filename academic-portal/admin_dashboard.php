<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
include 'connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | One Piece Academic Portal</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="theme-switcher.js"></script>
    <style>
        /* Make dashboard cards rectangular and small on this page */
        .dashboard-container {
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;
            max-width: 1200px !important;
        }
        .dashboard-card {
            min-height: 100px !important;
            border-radius: 12px !important;
            padding: 1.5rem !important;
            justify-content: center !important;
            gap: 0.5rem !important;
        }
        .dashboard-card i {
            margin-bottom: 0.5rem !important;
        }
        .dashboard-card h3 {
            font-size: 1.4rem !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        .dashboard-card h1 {
            font-size: 2.5rem !important;
            margin: 0 !important;
        }
        .dashboard-card p {
            margin-bottom: 0 !important;
            font-size: 0.9rem !important;
            padding: 0 10px !important;
        }
        .dashboard-card .btn {
            width: 100%;
            margin-top: auto;
            padding: 1rem;
        }

        /* ─── Admin-specific background override ─── */
        body::before {
            background:
                linear-gradient(rgba(15, 23, 42, 0.78), rgba(15, 23, 42, 0.78)),
                url('admin_portal_bg.png') !important;
            background-size: cover !important;
            background-position: center !important;
        }
        body.light-theme::before {
            background:
                linear-gradient(rgba(248, 250, 252, 0.55), rgba(248, 250, 252, 0.55)),
                url('admin_portal_bg.png') !important;
            background-size: cover !important;
            background-position: center !important;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <i class="fas fa-user-shield"></i> Admin Dashboard
        <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-muted); margin-left: 15px; border-left: 1px solid var(--glass-border); padding-left: 15px;">
            Welcome, Admin 👋
        </span>
    </div>
    
    <div class="nav-links">
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="?section=events"><i class="fas fa-calendar-check"></i> Events</a>
        <a href="?section=registrations"><i class="fas fa-users-viewfinder"></i> Registrations</a>
        <a href="?section=students"><i class="fas fa-user-graduate"></i> Student Accounts</a>
        <a href="#" onclick="confirmLogout()" style="color: var(--accent);"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <button id="theme-toggle" class="theme-toggle" title="Toggle Theme">
            <i class="fas fa-sun"></i>
        </button>
    </div>
</div>

<h1 class="title title-admin">Admin Control Panel</h1>

<?php
$section = $_GET['section'] ?? 'analytics';

if($section == 'registrations'){
    $event_filter = $_GET['event'] ?? '';
    $roll_filter = $_GET['rollno'] ?? '';
    echo "<h3 style='text-align:center; color: var(--text-muted); margin-bottom: 1rem;'>Registered Students Only</h3>";
    
    if ($event_filter || $roll_filter) {
        $filter_label = $event_filter ? "Event: " . htmlspecialchars($event_filter) : "Student: " . htmlspecialchars($roll_filter);
        echo "<p style='text-align: center; color: var(--secondary); margin-bottom: 1rem;'>
                <i class='fas fa-filter'></i> Filtered by: <strong>$filter_label</strong> 
                <a href='admin_dashboard.php?section=registrations' style='margin-left: 1rem; color: var(--accent); text-decoration: none;'><i class='fas fa-times-circle'></i> Clear Filter</a>
              </p>";
    }

    // Search Bar for Registrations Section
    echo "<div class='search-container'>
            <div class='search-wrapper'>
                <i class='fas fa-search'></i>
                <input type='text' id='regSearchInputOnly' class='search-input' placeholder='Search registered students...'>
            </div>
            <select id='regSearchCategoryOnly' class='search-select'>
                <option value='-1'>All Categories</option>
                <option value='1'>Name</option>
                <option value='2'>Roll No</option>
                <option value='4'>Department</option>
                <option value='6'>Event</option>
            </select>
          </div>";

    $query = "SELECT * FROM registrations";
    if ($event_filter) {
        $safe_event = mysqli_real_escape_string($conn, $event_filter);
        $query .= " WHERE event = '$safe_event'";
    } elseif ($roll_filter) {
        $safe_roll = mysqli_real_escape_string($conn, $roll_filter);
        $query .= " WHERE rollno = '$safe_roll'";
    }
    $result = mysqli_query($conn, $query);
    $serial_no = 1;
    echo "<div class='table-container'>
            <table id='registrationsTableOnly'>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Event</th>
                    <th>Action</th>
                </tr>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
                <td>" . $serial_no++ . "</td>
                <td>{$row['name']}</td>
                <td>{$row['rollno']}</td>
                <td>{$row['email']}</td>
                <td>{$row['mobile']}</td>
                <td>{$row['department']}</td>
                <td>{$row['year']}</td>
                <td>{$row['event']}</td>
                <td style='text-align: center;'>
                    <a href='delete.php?id={$row['id']}' style='color: var(--accent); text-decoration: none;' title='Remove' onclick=\"return confirm('Remove student from this event?')\"><i class='fas fa-trash'></i></a>
                </td>
              </tr>";
    }
    echo "</table></div>";
}

elseif($section == 'students'){
    echo "<h3 style='text-align:center; color: var(--text-muted); margin-bottom: 1rem;'>All Registered Student Accounts</h3>";
    
    // Search Bar for Students Section
    echo "<div class='search-container'>
            <div class='search-wrapper'>
                <i class='fas fa-search'></i>
                <input type='text' id='studentSearchInput' class='search-input' placeholder='Search student accounts...'>
            </div>
            <select id='studentSearchCategory' class='search-select'>
                <option value='-1'>All Categories</option>
                <option value='1'>Name</option>
                <option value='2'>Roll No</option>
                <option value='3'>Email</option>
                <option value='4'>Mobile</option>
            </select>
          </div>";

    $result = mysqli_query($conn, "SELECT * FROM students");
    $serial_no = 1;
    echo "<div class='table-container'>
            <table id='studentsTable'>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Mobile</th>
                    <th>Year</th>
                    <th>Action</th>
                </tr>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
                <td>" . $serial_no++ . "</td>
                <td>{$row['name']}</td>
                <td>" . ($row['rollno'] ?? '') . "</td>
                <td>{$row['email']}</td>
                <td style='color: var(--secondary); font-weight: 500;'>{$row['department']}</td>
                <td>" . ($row['mobile'] ?? '') . "</td>
                <td>" . ($row['year'] ?? '') . "</td>
                <td style='text-align: center;'>
                    <div style='display: flex; gap: 15px; justify-content: center;'>
                        <a href='admin_dashboard.php?section=registrations&rollno=" . urlencode($row['rollno']) . "' style='color: var(--secondary); text-decoration: none;' title='View Registrations'><i class='fas fa-address-card'></i></a>
                        <a href='delete_student.php?id={$row['id']}' style='color: var(--accent); text-decoration: none;' title='Delete Account' onclick=\"return confirm('Permanently delete this student account and all their registrations?')\"><i class='fas fa-user-slash'></i></a>
                    </div>
                </td>
              </tr>";
    }
    echo "</table></div>";
}

elseif($section == 'events'){
    echo "<h3 style='text-align:center; color: var(--text-muted); margin-bottom: 2rem;'><i class='fas fa-tasks'></i> Manage Events Only</h3>";
    echo "<div style='text-align:center; margin-bottom:2rem;'>
            <a href='add_event.php' class='btn'><i class='fas fa-plus'></i> Add New Event</a>
          </div>";

    // Search Bar for Events Section
    echo "<div class='search-container'>
            <div class='search-wrapper'>
                <i class='fas fa-search'></i>
                <input type='text' id='eventSearchInput' class='search-input' placeholder='Search events...'>
            </div>
            <select id='eventSearchCategory' class='search-select'>
                <option value='-1'>All Details</option>
                <option value='1'>Name</option>
                <option value='2'>Date</option>
                <option value='3'>Description</option>
            </select>
          </div>";
    
    $today = date('Y-m-d');
    $this_month_end = date('Y-m-t');
    $result = mysqli_query($conn, "SELECT DISTINCT category FROM events ORDER BY category ASC");
    $categories = [];
    while($cat_row = mysqli_fetch_assoc($result)) {
        $categories[] = $cat_row['category'] ?: 'Uncategorized';
    }

    function render_admin_events_table_rows($events, $conn, $category_label, $badge_class, $icon, $is_completed = false) {
        if (empty($events)) return;
        
        echo "<tr style='background: var(--glass-bg);'><td colspan='6' style='text-align: left; padding: 1rem 2rem;'><strong><i class='$icon'></i> $category_label</strong> <span class='badge $badge_class' style='margin-left: 10px;'>Category</span></td></tr>";
        
        $serial_no = 1;
        foreach($events as $row) {
            $event_name = $row['event_name'];
            $total_seats = $row['seats'];
            
            // Get registered count
            $reg_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations WHERE event='$event_name'");
            $reg_data = mysqli_fetch_assoc($reg_query);
            $filled_seats = $reg_data['count'];
            $available_seats = $total_seats - $filled_seats;

            $formatted_date = date('d-m-Y, l', strtotime($row['event_date']));
            $start_time = strtolower(date('ga', strtotime($row['event_time'])));

            echo "<tr>";
            echo "<td>".$serial_no++."</td>";
            echo "<td style='font-weight: 600;'>".$row['event_name']."</td>";
            echo "<td><i class='fas fa-calendar-day' style='color: var(--secondary);'></i> ".$formatted_date."</td>";
            echo "<td><div style='max-width:300px; font-size:0.85rem; color:var(--text-muted);'>".substr($row['description'], 0, 80)."...</div></td>";
            
            echo "<td><span style='color: var(--primary); font-weight:700;'>$filled_seats</span> / $total_seats <br> <small style='color: " . ($available_seats > 0 ? "var(--secondary)" : "var(--accent)") . ";'>$available_seats left</small></td>";
            
            echo "<td style='text-align: center;'>
                    <div style='display: flex; gap: 15px; justify-content: center;'>
                        <a href='admin_dashboard.php?section=registrations&event=" . urlencode($row['event_name']) . "' style='color: var(--secondary); text-decoration: none;' title='View Registered Students'><i class='fas fa-users-viewfinder'></i></a>
                        <a href='edit_event.php?id=".$row['id']."' style='color: var(--primary); text-decoration: none;' title='Edit'><i class='fas fa-edit'></i></a>
                        <a href='delete_event.php?id=".$row['id']."' style='color: var(--accent); text-decoration: none;' title='Delete' onclick=\"return confirm('Delete this event?')\"><i class='fas fa-trash'></i></a>
                    </div>
                  </td>";
            echo "</tr>";
        }
    }

    echo "<div class='table-container'>
            <table id='eventsTable' style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>DATE</th>
                        <th>DESCRIPTION</th>
                        <th>SEATS (FILLED/TOTAL)</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>";
    
    foreach($categories as $category) {
        $safe_cat = mysqli_real_escape_string($conn, $category);
        $cat_events_res = mysqli_query($conn, "SELECT * FROM events WHERE (category='$safe_cat' OR (category='' AND '$category'='Uncategorized')) ORDER BY event_date DESC");
        $cat_events = [];
        while($ce = mysqli_fetch_assoc($cat_events_res)) $cat_events[] = $ce;
        
        $icon = 'fas fa-tags';
        if(stripos($category, 'tech') !== false) $icon = 'fas fa-code';
        if(stripos($category, 'work') !== false) $icon = 'fas fa-chalkboard-teacher';
        if(stripos($category, 'sport') !== false) $icon = 'fas fa-running';

        render_admin_events_table_rows($cat_events, $conn, strtoupper($category), 'badge-month', $icon);
    }
    
    echo "</tbody></table></div>";
}

elseif($section == 'analytics'){

    // ── Stat Counts ────────────────────────────────────
    $total_events  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))['c'];
    $total_regs    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM registrations"))['c'];
    $total_students= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))['c'];

    // ── Registrations per Event (bar chart) ─────────────
    $reg_per_event_res = mysqli_query($conn, "SELECT event, COUNT(*) as cnt FROM registrations GROUP BY event ORDER BY cnt DESC LIMIT 8");
    $rpe_labels = []; $rpe_data = [];
    while($r = mysqli_fetch_assoc($reg_per_event_res)){ $rpe_labels[] = addslashes($r['event']); $rpe_data[] = (int)$r['cnt']; }

    // ── Registrations by Department (donut chart) ────────
    $dept_res = mysqli_query($conn, "SELECT department, COUNT(*) as cnt FROM registrations WHERE department != '' GROUP BY department ORDER BY cnt DESC");
    $dept_labels = []; $dept_data = [];
    while($r = mysqli_fetch_assoc($dept_res)){ $dept_labels[] = addslashes($r['department']); $dept_data[] = (int)$r['cnt']; }
    if(empty($dept_labels)){ $dept_labels = ['No Data']; $dept_data = [1]; }

    // ── Registrations by Category (area chart) ───────────
    $cat_res = mysqli_query($conn, "SELECT e.category, COUNT(r.id) as cnt FROM events e LEFT JOIN registrations r ON r.event=e.event_name GROUP BY e.category ORDER BY cnt DESC");
    $cat_labels = []; $cat_data = [];
    while($r = mysqli_fetch_assoc($cat_res)){ $cat_labels[] = addslashes($r['category'] ?: 'Uncategorized'); $cat_data[] = (int)$r['cnt']; }

    // ── Top Events fill rate (progress bars) ─────────────
    $top_res = mysqli_query($conn, "SELECT e.event_name, e.seats, COUNT(r.id) as filled FROM events e LEFT JOIN registrations r ON r.event=e.event_name GROUP BY e.id ORDER BY filled DESC LIMIT 6");
    $top_events = [];
    while($r = mysqli_fetch_assoc($top_res)) $top_events[] = $r;

    // ── JSON encode for JS ────────────────────────────────
    $rpe_labels_json = json_encode($rpe_labels);
    $rpe_data_json   = json_encode($rpe_data);
    $dept_labels_json= json_encode($dept_labels);
    $dept_data_json  = json_encode($dept_data);
    $cat_labels_json = json_encode($cat_labels);
    $cat_data_json   = json_encode($cat_data);

echo "
<!-- Chart.js CDN -->
<script src='https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0'></script>

<style>
  .analytics-wrap { padding: 0 3% 4rem; }

  /* Stat cards row */
  .stat-row { display:flex; gap:1.5rem; flex-wrap:wrap; margin-bottom:2rem; }
  .stat-card {
    flex:1; min-width:180px;
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius:24px; padding:1.8rem 2.2rem;
    display:flex; flex-direction:column; gap:0.6rem;
    animation: fadeInUp 0.6s ease-out backwards;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    color: inherit;
    transition: var(--transition);
  }
  .stat-card .sc-label { font-size:0.85rem; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); display:flex; align-items:center; gap:0.6rem; }
  .stat-card .sc-value { font-size:3rem; font-weight:800; color:var(--gold); line-height:1; text-shadow: 0 0 20px rgba(251, 191, 36, 0.2); }
  .stat-card .sc-sub   { font-size:0.85rem; color:var(--secondary); font-weight:600; opacity: 0.8; }
  .stat-card:nth-child(1){ border-top:3px solid var(--primary); animation-delay:0s; }
  .stat-card:nth-child(2){ border-top:3px solid var(--secondary); animation-delay:.1s; }
  .stat-card:nth-child(3){ border-top:3px solid #34d399; animation-delay:.2s; }
  .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.35); text-decoration: none; color: inherit; }
  a.stat-card { text-decoration: none; color: inherit; transition: all 0.2s ease; cursor: pointer; }

  /* Chart panels */
  .chart-row { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem; }
  .chart-row.full { grid-template-columns:1fr; }
  .chart-panel {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius:24px; padding:2rem;
    animation: fadeInUp 0.7s ease-out backwards;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
  }
  .chart-panel h3 { font-size:1rem; font-weight:700; color:var(--text-main); margin-bottom:1.2rem; display:flex; align-items:center; gap:0.5rem; }
  .chart-panel h3 i { color:var(--primary); }
  .chart-panel canvas { max-height:280px; }

  /* Top events progress */
  .top-event-row { margin-bottom:1rem; }
  .top-event-name { font-size:0.88rem; color:var(--text-main); margin-bottom:0.3rem; display:flex; justify-content:space-between; }
  .progress-track { background:rgba(255,255,255,0.08); border-radius:99px; height:10px; overflow:hidden; }
  .progress-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg, var(--primary), var(--secondary)); transition:width .8s ease; }

  body.light-theme .stat-card,
  body.light-theme .chart-panel {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  }
  body.light-theme .stat-card .sc-value { color: var(--primary); }
  body.light-theme .progress-track { background: rgba(0,0,0,0.07); }

  @media(max-width:768px){
    .chart-row { grid-template-columns:1fr; }
  }
</style>

<div class='analytics-wrap'>

  <!-- Stat Cards -->
  <div class='stat-row'>
    <a href='?section=events' class='stat-card'>
      <span class='sc-label'><i class='fas fa-calendar-alt'></i> Total Events <span style='margin-left:auto; opacity:0.6; font-size:0.75rem;'>1/3</span></span>
      <span class='sc-value'>$total_events</span>
      <span class='sc-sub'><i class='fas fa-arrow-up'></i> Active academic events</span>
    </a>
    <a href='?section=registrations' class='stat-card'>
      <span class='sc-label'><i class='fas fa-users'></i> Total Registrations <span style='margin-left:auto; opacity:0.6; font-size:0.75rem;'>2/3</span></span>
      <span class='sc-value'>$total_regs</span>
      <span class='sc-sub'><i class='fas fa-arrow-up'></i> Across all events</span>
    </a>
    <a href='?section=students' class='stat-card'>
      <span class='sc-label'><i class='fas fa-user-graduate'></i> Registered Students <span style='margin-left:auto; opacity:0.6; font-size:0.75rem;'>3/3</span></span>
      <span class='sc-value'>$total_students</span>
      <span class='sc-sub'><i class='fas fa-arrow-up'></i> Total student accounts</span>
    </a>
  </div>

  <!-- Row 1: Area chart (category) + Top events -->
  <div class='chart-row'>
    <div class='chart-panel'>
      <h3><i class='fas fa-chart-area'></i> Registrations by Category</h3>
      <canvas id='catChart'></canvas>
    </div>
    <div class='chart-panel'>
      <h3><i class='fas fa-trophy'></i> Top Events – Seat Fill Rate</h3>";

    foreach($top_events as $te){
        $pct = $te['seats'] > 0 ? round(($te['filled']/$te['seats'])*100) : 0;
        $name = htmlspecialchars($te['event_name']);
        echo "
      <div class='top-event-row'>
        <div class='top-event-name'><span>$name</span><span style='color:var(--secondary);'>$te[filled]/$te[seats] &nbsp;($pct%)</span></div>
        <div class='progress-track'><div class='progress-fill' style='width:{$pct}%'></div></div>
      </div>";
    }

    echo "
    </div>
  </div>

  <!-- Row 2: Bar + Donut -->
  <div class='chart-row'>
    <div class='chart-panel'>
      <h3><i class='fas fa-chart-bar'></i> Registrations per Event</h3>
      <canvas id='barChart'></canvas>
    </div>
    <div class='chart-panel'>
      <h3><i class='fas fa-chart-pie'></i> Students by Department</h3>
      <canvas id='donutChart'></canvas>
    </div>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

  let catChartIns, barChartIns, donutChartIns;

  function getThemeColors() {
      const isLight = document.documentElement.classList.contains('light-theme') || 
                      document.body.classList.contains('light-theme') ||
                      localStorage.getItem('theme') === 'light';
      return {
          textCol: isLight ? '#0f172a' : '#e2e8f0',
          gridCol: isLight ? 'rgba(15, 23, 42, 0.08)' : 'rgba(255,255,255,0.08)',
          donutBorder: isLight ? '#ffffff' : '#0f172a',
          dataLabelCol: isLight ? '#0f172a' : '#ffffff'
      };
  }

  function applyChartColors() {
      const colors = getThemeColors();
      Chart.defaults.color = colors.textCol;
      Chart.defaults.borderColor = colors.gridCol;
      
      [catChartIns, barChartIns, donutChartIns].forEach(chart => {
          if (!chart) return;
          
          // Update Scales
          if (chart.options.scales) {
              Object.values(chart.options.scales).forEach(scale => {
                  scale.ticks = scale.ticks || {};
                  scale.ticks.color = colors.textCol;
                  scale.ticks.font = { weight: '700', family: 'Outfit, sans-serif' };
                  scale.grid = scale.grid || {};
                  scale.grid.color = colors.gridCol;
              });
          }

          // Update Legends
          if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
              chart.options.plugins.legend.labels.color = colors.textCol;
              chart.options.plugins.legend.labels.font = { weight: '600' };
          }

          // Update DataLabels Plugin (CRITICAL FIX)
          if (chart.options.plugins && chart.options.plugins.datalabels) {
              chart.options.plugins.datalabels.color = colors.dataLabelCol;
          }

          chart.update();
      });

      if (donutChartIns) {
          donutChartIns.data.datasets[0].borderColor = colors.donutBorder;
          donutChartIns.update();
      }
  }

  const initialColors = getThemeColors();
  Chart.defaults.color = initialColors.textCol;
  Chart.defaults.borderColor = initialColors.gridCol;
  Chart.defaults.font.family = 'Outfit, sans-serif';

  // ── Area/Line chart – Registrations by Category ─────
  const catChartCanvas = document.getElementById('catChart');
  const catCtx = catChartCanvas.getContext('2d');
  
  const lineGradient = catCtx.createLinearGradient(0, 0, 400, 0);
  lineGradient.addColorStop(0, '#0ea5e9');
  lineGradient.addColorStop(0.5, '#d946ef');
  lineGradient.addColorStop(1, '#f59e0b');
  
  const fillGradient = catCtx.createLinearGradient(0, 0, 0, 300);
  fillGradient.addColorStop(0, 'rgba(217, 70, 239, 0.45)');
  fillGradient.addColorStop(1, 'rgba(14, 165, 233, 0.05)');

  catChartIns = new Chart(catCtx, {
    type: 'line',
    data: {
      labels: $cat_labels_json,
      datasets: [{
        label: 'Registrations',
        data: $cat_data_json,
        fill: true,
        tension: 0.45,
        borderColor: lineGradient,
        backgroundColor: fillGradient,
        pointBackgroundColor: '#ffffff',
        pointRadius: 5,
        borderWidth: 2.5
      }]
    },
    options: {
      responsive:true, maintainAspectRatio:true,
      plugins:{ legend:{ display:false } },
      scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } }
    }
  });

  // ── Bar chart – Registrations per Event ─────────────
  const barCtx = document.getElementById('barChart').getContext('2d');
  barChartIns = new Chart(barCtx, {
    type: 'bar',
    plugins: [ChartDataLabels],
    data: {
      labels: $rpe_labels_json,
      datasets: [{
        label: 'Registrations',
        data: $rpe_data_json,
        backgroundColor: [
          'rgba(129,140,248,0.8)','rgba(56,189,248,0.8)','rgba(52,211,153,0.8)',
          'rgba(251,113,133,0.8)','rgba(245,158,11,0.8)','rgba(217,70,239,0.8)',
          'rgba(14,165,233,0.8)','rgba(99,102,241,0.8)'
        ],
        borderRadius: 8,
        borderWidth: 0
      }]
    },
    options: {
      responsive:true, maintainAspectRatio:true,
      plugins:{ 
        legend:{ display:false },
        datalabels: {
          color: '#ffffff',
          anchor: 'end',
          align: 'bottom',
          offset: 4,
          font: { weight: 'bold', size: 13, family: 'Outfit, sans-serif' },
          formatter: (value) => value
        }
      },
      scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } },
               x:{ ticks:{ maxRotation:30 } } }
    }
  });

  // ── Donut chart – By Department ─────────────────────
  const donutCtx = document.getElementById('donutChart').getContext('2d');
  donutChartIns = new Chart(donutCtx, {
    type: 'doughnut',
    plugins: [ChartDataLabels],
    data: {
      labels: $dept_labels_json,
      datasets: [{
        data: $dept_data_json,
        backgroundColor:[
          '#818cf8','#38bdf8','#34d399','#fb7185',
          '#f59e0b','#d946ef','#0ea5e9','#6366f1'
        ],
        borderWidth: 2,
        borderColor: initialColors.donutBorder
      }]
    },
    options: {
      responsive:true, maintainAspectRatio:true,
      cutout:'55%',
      plugins:{
        legend:{ position:'right', labels:{ boxWidth:14, padding:14, font:{size:12} } },
        datalabels: {
          color: '#ffffff',
          font: { weight: 'bold', size: 14, family: 'Outfit, sans-serif' },
          formatter: (value, ctx) => {
            let sum = 0;
            let dataArr = ctx.chart.data.datasets[0].data;
            dataArr.forEach(data => { sum += Number(data); });
            let percentage = (value * 100 / sum).toFixed(0) + '%';
            return percentage;
          }
        }
      }
    }
  });

  // Call immediately after initialization to ensure correct theme on load
  setTimeout(applyChartColors, 100);

  // ── Listen for theme changes ─────────────
  const toggleBtnObj = document.getElementById('theme-toggle');
  if (toggleBtnObj) {
      toggleBtnObj.addEventListener('click', () => {
          setTimeout(applyChartColors, 50);
      });
  }
});
</script>";

}
?>

<script>
function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
    }
}

// Global Filtering Function
function setupTableSearch(tableId, inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);
    const table = document.getElementById(tableId);
    
    if (!input || !select || !table) return;

    function applyFilter() {
        const filterText = input.value.toLowerCase();
        const colIdx = parseInt(select.value);
        const rows = table.querySelectorAll('tbody tr, tr'); // Handle both thead and no-thead tables

        rows.forEach((row, index) => {
            // Skip header if it's the first row and not in <thead>
            if (row.parentElement.tagName !== 'THEAD' && (row.querySelector('th') || index === 0 && tableId !== 'eventsTable')) {
                return;
            }
            
            // For the Events table, skip the category group header rows which have a colspan
            if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                // Keep the category headers always visible if any row in their group matches? 
                // Or just keep them visible. For now, keep them visible.
                row.style.display = "";
                return;
            }

            let matches = false;
            const cells = row.cells;
            
            if (colIdx === -1) {
                // Search All Columns
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filterText)) {
                        matches = true;
                        break;
                    }
                }
            } else if (cells[colIdx]) {
                // Search Specific Column
                if (cells[colIdx].textContent.toLowerCase().includes(filterText)) {
                    matches = true;
                }
            }
            
            row.style.display = matches ? "" : "none";
        });
    }

    input.addEventListener('keyup', applyFilter);
    select.addEventListener('change', applyFilter);
}

// Initialize searches based on current section
document.addEventListener('DOMContentLoaded', () => {
    setupTableSearch('registrationsTable', 'regSearchInput', 'regSearchCategory');
    setupTableSearch('registrationsTableOnly', 'regSearchInputOnly', 'regSearchCategoryOnly');
    setupTableSearch('studentsTable', 'studentSearchInput', 'studentSearchCategory');
    setupTableSearch('eventsTable', 'eventSearchInput', 'eventSearchCategory');
});
</script>


</body>
</html>