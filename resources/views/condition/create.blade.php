<x-guest-layout>
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #2e1065 !important; letter-spacing: -0.5px;">Eye Condition Record</h3>
        <p class="text-muted small">Enter the optical prescription and clinical eye conditions for the customer.</p>
        <hr style="border-color: #e9d5ff;">
    </div>

    <!-- Successful Message -->
    @if(session('success'))
        <div class="alert alert-success border-0 py-2 text-center rounded-3 mb-4" style="font-size: 0.85rem; background-color: #f5f3ff; color: #6d28d9;">
            ✨ Preskripsi berjaya disimpan dengan selamat!
        </div>
    @endif

    <form method="POST" action="{{ route('condition.store') }}">
        @csrf

        <!--(AppointmentID & PD) -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label text-muted fw-medium small mb-1">Appointment ID</label>
                <div class="input-group">
                    <input type="number" name="appointmentID" class="form-control shadow-sm border-0 py-2 px-3 rounded-start-3" style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #f3e8ff !important;" placeholder="Search or enter ID (e.g. 1001)" required>
                    <button class="btn btn-outline-secondary shadow-sm py-2 px-3 rounded-end-3" type="button" style="border: 1px solid #f3e8ff; background-color: #f5f3ff; color: #7c3aed;">
                        Find
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted fw-medium small mb-1">Pupil Distance (PD)</label>
                <!-- 🌟 Pupillary Distance value limit -->
                <input type="number" name="pupilDistance" class="form-control shadow-sm border-0 py-2 px-3 rounded-3" style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #f3e8ff !important;" placeholder="mm" min="40" max="80" required>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Right Eye -->
            <div class="col-lg-6">
                <div class="p-4 rounded-4 h-100" style="background-color: #faf5ff; border: 1px dashed #d8b4fe;">
                    <h6 class="fw-bold mb-3 small text-uppercase tracking-wider" style="color: #7c3aed;">Right Eye (OD)</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Power (SPH)</label>
                            <!-- Limit value SPH for right eye -->
                            <input type="number" step="0.01" min="-20.00" max="20.00" name="rightPower" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="0.00" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Cylinder</label>
                            <!-- Limit value Cylinder for right eye-->
                            <input type="number" step="0.01" min="-10.00" max="10.00" name="rightCylinder" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="0.00" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Axis</label>
                            <!-- axis limit value 0-180 -->
                            <input type="number" min="0" max="180" name="rightAxis" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="°" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Eye-->
            <div class="col-lg-6">
                <div class="p-4 rounded-4 h-100" style="background-color: #f5f3ff; border: 1px dashed #c084fc;">
                    <h6 class="fw-bold mb-3 small text-uppercase tracking-wider" style="color: #6d28d9;">Left Eye (OS)</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Power (SPH)</label>
                            <!-- limit value SPH for left eye -->
                            <input type="number" step="0.01" min="-20.00" max="20.00" name="leftPower" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="0.00" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Cylinder</label>
                            <!-- limit value Cylinder for left eye-->
                            <input type="number" step="0.01" min="-10.00" max="10.00" name="leftCylinder" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="0.00" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small mb-1">Axis</label>
                            <!-- axis limit value 0-180 -->
                            <input type="number" min="0" max="180" name="leftAxis" class="form-control border-0 shadow-sm py-2" style="font-size: 0.85rem;" placeholder="°" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- navigation button -->
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 pt-2">
            <div>
                <!-- back button-->
                <a href="{{ url()->previous() }}" class="btn btn-light py-2 px-4 rounded-3 border-0 text-muted fw-medium small" style="background-color: #f3f4f6; text-decoration: none;">
                    Back
                </a>
            </div>
            <div class="d-flex gap-2">
                <!-- BUTTON 1: Save Only -->
                <button type="submit" name="action" value="save_only" class="btn py-2 px-4 rounded-3 fw-semibold small border-0 shadow-sm" style="background-color: #f3e8ff; color: #4c1d95;">
                    Save Only
                </button>

                <!-- BUTTON 2: Save & Proceed to Create Order (Teal Korporat) -->
                <button type="submit" name="action" value="save_and_order" class="btn py-2 px-4 rounded-3 fw-semibold small border-0 shadow-sm text-white" style="background-color: #0d9488;">
                    Save & Proceed to Create Order <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </button>
            </div>
        </div>
    </form>
</x-guest-layout>
