<div class="page-header">
    <div class="page-title">
        <h3>{{@$title}}</h3>
        <span>Hello, {{Auth::user()->name}}!</span>
    </div>

    <!-- Page Stats -->
    <!--ul class="page-stats">
        <li>
            <div class="summary">
                <span>Completed Samples</span>
                <h3>{{Session::get('completed')}}</h3>
            </div>
            <div id="sparkline-bar" class="graph sparkline hidden-xs">20,15,8,50,20,40,20,30,20,15,30,20,25,20</div>
            <!-- Use instead of sparkline e.g. this:
            <div class="graph circular-chart" data-percent="73">73%</div>
            -->
        <!--/li>
        <li>
            <div class="summary">
                <span>Pending Samples</span>
                <h3>{{Session::get('pending')}}</h3>
            </div>
            <div id="sparkline-bar2" class="graph sparkline hidden-xs">20,15,8,50,20,40,20,30,20,15,30,20,25,20</div>
        </li>
    </ul>
    <!-- /Page Stats -->
</div>