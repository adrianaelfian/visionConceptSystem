<x-guest-layout>
    <!-- Dashboard Header -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #2e1065 !important; letter-spacing: -0.5px;">
            Welcome Back, {{ auth()->check() ? auth()->user()->name : 'Staff' }}!
        </h3>
        <p class="text-muted small">Here is the operational overview for Dynamic Concept Eyewear (GM Klang) today.</p>
        <hr style="border-color: #e9d5ff;">
    </div>

    <!-- 4 OPERATIONAL SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Appointments Today -->
        <div class="col-md-6 col-lg-3">
            <div class="p-3 rounded-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-uppercase" style="letter-spacing: 0.5px; opacity: 0.95;">Appointments Today</span>
                    <i class="fa-solid fa-calendar-day fs-4" style="opacity: 0.8;"></i>
                </div>
                <h2 class="fw-bold m-0">12</h2>
                <small class="fw-medium text-white" style="font-size: 0.75rem;">4 pending confirmation</small>
            </div>
        </div>

        <!-- Card 2: Eye Tests Done -->
        <div class="col-md-6 col-lg-3">
            <div class="p-3 rounded-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-uppercase" style="letter-spacing: 0.5px; opacity: 0.95;">Eye Tests Done</span>
                    <i class="fa-solid fa-eye fs-4" style="opacity: 0.8;"></i>
                </div>
                <h2 class="fw-bold m-0">8</h2>
                <small class="fw-medium text-white" style="font-size: 0.75rem;">By active optometrists</small>
            </div>
        </div>

        <!-- Card 3: New Orders  -->
        <div class="col-md-6 col-lg-3">
            <div class="p-3 rounded-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-uppercase" style="letter-spacing: 0.5px; opacity: 0.95;">New Orders</span>
                    <i class="fa-solid fa-shopping-bag fs-4" style="opacity: 0.8;"></i>
                </div>
                <h2 class="fw-bold m-0">15</h2>
                <small class="fw-medium text-white" style="font-size: 0.75rem;">Ready for lab processing</small>
            </div>
        </div>

        <!-- Card 4: Today's Revenue  -->
        <div class="col-md-6 col-lg-3">
            <div class="p-3 rounded-4 border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-uppercase" style="letter-spacing: 0.5px; opacity: 0.95;">Today's Revenue</span>
                    <i class="fa-solid fa-wallet fs-4" style="opacity: 0.8;"></i>
                </div>
                <h2 class="fw-bold m-0">RM 4,250</h2>
                <small class="fw-medium text-white" style="font-size: 0.75rem;">+12% from yesterday</small>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #fbfbfe; border: 1px solid #e9d5ff !important;">
        <h6 class="fw-bold mb-3" style="color: #4c1d95;"><i class="fa-solid fa-bolt me-2 text-warning"></i>Quick Staff Actions</h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="btn btn-sm btn-light py-2 px-3 rounded-3 fw-medium text-secondary bg-white border"><i class="fa-solid fa-user-plus me-1 text-primary"></i> Register Customer</a>
            <a href="#" class="btn btn-sm btn-light py-2 px-3 rounded-3 fw-medium text-secondary bg-white border"><i class="fa-solid fa-calendar-plus me-1 text-success"></i> Book Appointment</a>
            <a href="{{ route('condition.create') }}" class="btn btn-sm py-2 px-3 rounded-3 fw-semibold text-white shadow-sm" style="background-color: #6d28d9;"><i class="fa-solid fa-eye-dropper me-1"></i> New Eye Test Record</a>
        </div>
    </div>

    <!-- 3. list of upcoming appointment table -->
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold m-0" style="color: #2e1065;"><i class="fa-solid fa-list-check me-2" style="color: #6d28d9;"></i>Today's Appointment Queue</h6>
        <span class="badge rounded-pill px-3 py-2" style="background-color: #f3e8ff; color: #6d28d9; font-size: 0.75rem; font-weight: 600;">Active Queue</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0" style="font-size: 0.9rem;">
            <!-- ... (thead kod asal awak tetap di sini) ... -->

            <tbody>
                <!-- 🌟 SAFETY CHECK: Jika variable tak wujud, gunakan array kosong supaya tidak ralat -->
                @php
                    $appointments = isset($activeAppointments) ? $activeAppointments : [];
                @endphp

                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-3 fw-bold" style="color: #6d28d9;">#{{ $appointment->id }}</td>
                        <td class="fw-medium text-dark">{{ $appointment->customer_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning-emphasis border-0 px-2 py-1 rounded-2" style="font-size: 0.75rem; font-weight: 600;">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="text-end px-3">
                            <a href="{{ route('condition.create', ['appointmentID' => $appointment->id]) }}" class="btn btn-sm text-white px-3 rounded-3 border-0 shadow-sm fw-semibold" style="background-color: #0d9488; font-size: 0.8rem;">
                                Start Eye Test <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4 small">
                            No active appointments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-guest-layout>
