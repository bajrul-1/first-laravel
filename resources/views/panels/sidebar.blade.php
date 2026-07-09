<div class="ty-4 px-3">
    <h4 class="fw-bold text-primary mb-0">Company SAS</h4>
    <p class="text-muted small">Super Admin Framework</p>
</div>
<ul class="nav flax-column">
    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->is('company*') ? 'active' : '' }}" href="companies">Companies</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Personnel</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Expenses</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>

</ul>