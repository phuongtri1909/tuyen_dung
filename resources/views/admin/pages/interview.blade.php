@extends('admin.layouts.app')

@section('content-auth')
    <div class="card mb-4 mx-md-4">
        <div class="card-header pb-0">
            <h5 class="mb-0">INTERVIEW EVALUATION FORM FOR STAFF POSITION</h5>
        </div>
        <div class="card-body pt-4 p-3">
            <!-- Form phỏng vấn -->
            <div class="form-container">

                @include('admin.pages.components.success-error')

                <form action="{{ route('candidates.save-interview', $candidate->id) }}" method="POST" class="interview-form">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="full_name">Candidate’s full name:</label>
                                <input type="text" name="full_name" id="full_name" class="form-control"
                                    value="{{ $candidate->full_name ?? '' }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="desired_position">Desired position:</label>
                                <input type="text" name="desired_position" id="desired_position" class="form-control"
                                    value="{{ $candidate->desired_position ?? '' }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="outlet_department">Outlet/Department: </label>
                                <input type="text" name="outlet_department" id="outlet_department" class="form-control"
                                    value="{{ $candidate->outlet_department ?? '' }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Employment type: </label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="employment_type" value="full-time" id="fulltime"
                                            {{ ($candidate->employment_type ?? '') == 'full-time' ? 'checked' : '' }}>
                                        <span>Full-time</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="employment_type" value="casual-labor" id="casual"
                                            {{ ($candidate->employment_type ?? '') == 'casual-labor' ? 'checked' : '' }}>
                                        <span>Casual Labor</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng người phỏng vấn -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="section-title">Name of interviewers</h6>
                            <table class="interviewers-table">

                                <tr>
                                    <td>HR</td>
                                    <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="text"
                                            name="hr_name" class="form-control" value="{{ $candidate->hr_name ?? '' }}">
                                    </td>
                                    <td>Date</td>
                                    <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="date"
                                            name="hr_date" class="form-control"
                                            value="{{ $candidate->hr_date ?? now()->format('Y-m-d') }}"></td>
                                </tr>

                                @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                    <tr>
                                        <td>LM</td>
                                        <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="text"
                                                name="lm_name" class="form-control"
                                                value="{{ $candidate->lm_name ?? '' }}"></td>
                                        <td>Date</td>
                                        <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="date"
                                                name="lm_date" class="form-control"
                                                value="{{ $candidate->lm_date ?? now()->format('Y-m-d') }}"></td>
                                    </tr>
                                @endif

                                @if (auth()->user()->role == 'final')
                                    <tr>
                                        <td>Final</td>
                                        <td><input type="text" name="final_name" class="form-control"
                                                value="{{ $candidate->final_name ?? '' }}"></td>
                                        <td>Date</td>
                                        <td><input type="date" name="final_date" class="form-control"
                                                value="{{ $candidate->final_date ?? now()->format('Y-m-d') }}"></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Khả năng làm việc -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="section-title">Position requirements (Yêu cầu cơ bản cho vị trí này):</h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>1. Can work on holidays and weekends? (Có thể làm việc vào ngày lễ và cuối tuần
                                    không?)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_holidays" value="1"
                                            id="can_work_holidays_yes"
                                            {{ ($candidate->can_work_holidays ?? '') == 1 ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_holidays" value="0"
                                            id="can_work_holidays_no"
                                            {{ ($candidate->can_work_holidays ?? '') === 0 ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>2. Can work different shifts? (Có thể làm các ca khác nhau không?)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_different_shifts" value="1"
                                            id="can_work_different_shifts_yes"
                                            {{ ($candidate->can_work_different_shifts ?? '') == 1 ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_different_shifts" value="0"
                                            id="can_work_different_shifts_no"
                                            {{ ($candidate->can_work_different_shifts ?? '') === 0 ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>3. Can work split shifts? (Có sẵn sàng làm ca gãy không?)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_split_shifts" value="1"
                                            id="can_work_split_shifts_yes"
                                            {{ ($candidate->can_work_split_shifts ?? '') == 1 ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_split_shifts" value="0"
                                            id="can_work_split_shifts_no"
                                            {{ ($candidate->can_work_split_shifts ?? '') === 0 ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>4. Can work overtime? (Có sẵn sàng làm thêm giờ không?)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_overtime" value="1"
                                            id="can_work_overtime_yes"
                                            {{ ($candidate->can_work_overtime ?? '') == 1 ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_overtime" value="0"
                                            id="can_work_overtime_no"
                                            {{ ($candidate->can_work_overtime ?? '') === 0 ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>5. Can work late shift? (Có thể làm khuya không?)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_late_shift" value="1"
                                            id="can_work_late_shift_yes"
                                            {{ ($candidate->can_work_late_shift ?? '') == 1 ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="can_work_late_shift" value="0"
                                            id="can_work_late_shift_no"
                                            {{ ($candidate->can_work_late_shift ?? '') === 0 ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="notice_days">6. Required notice days/Báo trước bao nhiêu ngày: </label>
                                <input type="number" name="notice_days" id="notice_days" class="form-control"
                                    value="{{ $candidate->notice_days ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="available_date">Or/hoặc Available/Ngày sẵn sàng đi làm: </label>
                                <input type="date" name="available_date" id="available_date" class="form-control"
                                    value="{{ $candidate->available_date ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="min_salary">7. Minimum salary expectation/Mức lương mong muốn: </label>
                                <input type="text" name="min_salary" id="min_salary" class="form-control"
                                    value="{{ $candidate->min_salary ?? '' }}">
                                <div class="help-text">USD/VND</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng đánh giá -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="section-title">Rate each point below from 1 to 10 (best)/Chấm điểm mỗi phần dưới đây
                                từ 1-10 (cao nhất):</h6>
                            <p class="help-text mb-3"><em>Note: LM = Line Manager/ Quản lý Trực tiếp</em></p>

                            <table class="rating-table">
                                <thead>
                                    <tr>
                                        <th width="60%"></th>
                                        <th>HR</th>

                                        <th>LM</th>

                                        <th>Final</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Appearance & Attire -->
                                    <tr>
                                        <td>
                                            <strong>1. Appearance & Attire/ Dung mạo & Trang phục</strong>
                                            <div class="criteria-description">Candidate represents Phe La grooming standard
                                                (clothes, hair, make-up, body language, …)</div>
                                        </td>

                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_appearance" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['appearance'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_appearance" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['appearance'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_appearance" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['appearance'] ?? '' }}"></td>
                                        @endif

                                    </tr>

                                    <!-- English -->
                                    <tr>
                                        <td>
                                            <strong>2. English/ Tiếng Anh</strong>
                                            <div class="criteria-description">Candidate demonstrates the ability to
                                                communicate effectively.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_english" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['english'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_english" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['english'] ?? '' }}">
                                            </td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_english" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['english'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Chinese -->
                                    <tr>
                                        <td>
                                            <strong>3. Chinese/ Tiếng Trung</strong>
                                            <div class="criteria-description">Candidate demonstrates the ability to
                                                communicate effectively</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_chinese" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['chinese'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_chinese" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['chinese'] ?? '' }}">
                                            </td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_chinese" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['chinese'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Japanese -->
                                    <tr>
                                        <td>
                                            <strong>4. Japanese/ Tiếng Nhật</strong>
                                            <div class="criteria-description">Candidate demonstrates the ability to
                                                communicate effectively.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_japanese" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['japanese'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_japanese" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['japanese'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_japanese" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['japanese'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Computer skills -->
                                    <tr>
                                        <td>
                                            <strong>5. Computer skills/ Kỹ năng vi tính</strong>
                                            <div class="criteria-description">What software the candidate uses that will
                                                support their work.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_computer" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['computer'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_computer" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['computer'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_computer" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['computer'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Behavior during interview -->
                                    <tr>
                                        <td>
                                            <strong>6. Behavior during interview/ Ứng xử trong phỏng vấn</strong>
                                            <div class="criteria-description">Candidate has proper attitude, manner,
                                                posture and eyes contact…</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_behavior" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['behavior'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_behavior" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['behavior'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_behavior" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['behavior'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Characteristics -->
                                    <tr>
                                        <td>
                                            <strong>7. Characteristics/ Tính cách</strong>
                                            <div class="criteria-description">Candidate possesses appropriate traits for
                                                service industry and for further development (outgoing, cooperative,
                                                attentive, confident, enthusiastic, maturi…)</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_characteristics" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['characteristics'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_characteristics" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['characteristics'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_characteristics" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['characteristics'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Communication skills -->
                                    <tr>
                                        <td>
                                            <strong>8. Communication skills/ Kỹ năng giao tiếp</strong>
                                            <div class="criteria-description">Candidate demonstrates the ability to
                                                articulate the answers or to initiate questions.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_communication" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['communication'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_communication" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['communication'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_communication" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['communication'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Motivation -->
                                    <tr>
                                        <td>
                                            <strong>9. Motivation/ Động lực ứng tuyển</strong>
                                            <div class="criteria-description">Candidate demonstrates a right motivation for
                                                the position and for Phe La through his/her knowledges of organization,
                                                reason of interest, level of commitment, …</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_motivation" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['motivation'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_motivation" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['motivation'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_motivation" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['motivation'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Experience from previous jobs -->
                                    <tr>
                                        <td>
                                            <strong>10. Experience from previous jobs/ Kinh nghiệm từ công việc trước
                                                đây</strong>
                                            <div class="criteria-description">Candidate has relevant experience/technical
                                                skills for the desired position.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_experience" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['experience'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_experience" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['experience'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_experience" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['experience'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Customer handling experience -->
                                    <tr>
                                        <td>
                                            <strong>11. Customer handling experience/ Kinh nghiệm giải quyết vấn đề với
                                                khách hàng</strong>
                                            <div class="criteria-description">Give true experience about how candidate has
                                                handled unusual situation with customer, overcome complaint, exceeded their
                                                expectation…</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_customer" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['customer'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_customer" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['customer'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_customer" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['customer'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Flexibility -->
                                    <tr>
                                        <td>
                                            <strong>12. Flexibility/ Sự linh hoạt, mềm mỏng trong công việc</strong>
                                            <div class="criteria-description">Ask how candidate reacts when being
                                                instructed to do work in an unusual way. Evaluate if they react positively,
                                                do the work with sense of urgency and respect.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_flexibility" min="1" max="10"
                                                class="rating-input"
                                                value="{{ $evaluations['hr']['flexibility'] ?? '' }}"></td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_flexibility" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['flexibility'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_flexibility" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['flexibility'] ?? '' }}"></td>
                                        @endif
                                    </tr>

                                    <!-- Teamwork -->
                                    <tr>
                                        <td>
                                            <strong>13. Teamwork/ Tinh thần tập thể</strong>
                                            <div class="criteria-description">Ask how candidate enjoys team working,
                                                willing to take difficult tasks, put team above individual.</div>
                                        </td>
                                        <td><input {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} type="number"
                                                name="hr_rating_teamwork" min="1" max="10"
                                                class="rating-input" value="{{ $evaluations['hr']['teamwork'] ?? '' }}">
                                        </td>

                                        @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                            <td><input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} type="number"
                                                    name="lm_rating_teamwork" min="1" max="10"
                                                    class="rating-input"
                                                    value="{{ $evaluations['lm']['teamwork'] ?? '' }}"></td>
                                        @endif

                                        @if (auth()->user()->role == 'final')
                                            <td><input type="number" name="final_rating_teamwork" min="1"
                                                    max="10" class="rating-input"
                                                    value="{{ $evaluations['final']['teamwork'] ?? '' }}"></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tổng điểm đánh giá -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="section-title">Overall Score</h6>

                            <table class="rating-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Total Score</th>
                                        <th>Average score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>HR</td>
                                        <td><input type="number" name="hr_total_score" class="form-control" readonly
                                                value="{{ $totalScores['hr'] ?? 0 }}">
                                        </td>
                                        <td rowspan="3" style="vertical-align: middle; text-align: center;">
                                            <input type="number" name="average_score" class="form-control" readonly
                                                value="{{ $averageScore ?? 0 }}" style="width: 80px; margin: 0 auto;">
                                            <div class="mt-2">
                                                <span class="badge bg-danger d-none"
                                                    id="unsatisfactory-badge">Unsatisfactory / Không đạt </span>
                                                <span class="badge bg-primary d-none" id="good-badge">Good / Đạt</span>
                                                <span class="badge bg-success d-none" id="excellent-badge">Excellent /
                                                    Xuất sắc </span>
                                            </div>
                                        </td>
                                    </tr>

                                    @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                        <tr>
                                            <td>LM</td>
                                            <td><input type="number" name="lm_total_score" class="form-control" readonly
                                                    value="{{ $totalScores['lm'] ?? 0 }}">
                                            </td>
                                        </tr>
                                    @endif

                                    @if (auth()->user()->role == 'final')
                                        <tr>
                                            <td>Final</td>
                                            <td><input type="number" name="final_total_score" class="form-control"
                                                    readonly value="{{ $totalScores['final'] ?? 0 }}">
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div class="card bg-light mt-3">
                                <div class="card-body p-3">
                                    <p class="mb-2"><strong>Average Score from 3
                                            interview (total overall
                                            scores and divide by 3)</strong></p>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="badge bg-danger">Unsatisfactory / Không đạt </span>
                                            <span class="ms-2">1-52</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary">Good / Đạt</span>
                                            <span class="ms-2">53-104</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">Excellent / Xuất sắc </span>
                                            <span class="ms-2">105-130</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đề xuất hành động -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="section-title">Recommended Action</h6>

                            <table class="recommendation-table">

                                <tr>
                                    <th>Nhân sự<br>Human Resources</th>
                                    <th>
                                        <div class="form-group">
                                            <textarea {{ auth()->user()->role != 'hr' ? 'disabled' : '' }} name="action_hr" class="form-control">{{ $recommendations['hr']['action'] ?? '' }}</textarea>
                                        </div>
                                    </th>
                                    <td class="w-10">
                                        <div class="signature-box">
                                            <!-- Chỗ để ký tên -->
                                        </div>
                                        <div class="form-group">
                                            <label for="hr_signature_date">Ngày ký / Date:</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Propose next step</th>
                                    <td colspan="2">
                                        <div class="checkbox-group">
                                            <label>
                                                <input type="radio" name="hr_recommendation" value="highly_recommend"
                                                    {{ isset($recommendations['hr']) && $recommendations['hr']['propose_next_step'] == 'highly_recommend' ? 'checked' : '' }}
                                                    {{ auth()->user()->role != 'hr' ? 'disabled' : '' }}>
                                                <span>Highly recommend</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="hr_recommendation" value="recommend"
                                                    {{ isset($recommendations['hr']) && $recommendations['hr']['propose_next_step'] == 'recommend' ? 'checked' : '' }}
                                                    {{ auth()->user()->role != 'hr' ? 'disabled' : '' }}>
                                                <span>Recommend</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="hr_recommendation" value="do_not_recommend"
                                                    {{ isset($recommendations['hr']) && $recommendations['hr']['propose_next_step'] == 'do_not_recommend' ? 'checked' : '' }}
                                                    {{ auth()->user()->role != 'hr' ? 'disabled' : '' }}>
                                                <span>Do not recommend</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="hr_recommendation" value="hold_consider"
                                                    {{ isset($recommendations['hr']) && $recommendations['hr']['propose_next_step'] == 'hold_consider' ? 'checked' : '' }}
                                                    {{ auth()->user()->role != 'hr' ? 'disabled' : '' }}>
                                                <span>Hold Consider</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="hr_recommendation" value="other_position"
                                                    {{ isset($recommendations['hr']) && $recommendations['hr']['propose_next_step'] == 'other_position' ? 'checked' : '' }}
                                                    {{ auth()->user()->role != 'hr' ? 'disabled' : '' }}>
                                                <span>Other position</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>

                                @if (auth()->user()->role == 'lm' || auth()->user()->role == 'final')
                                    <tr>
                                        <th>Quản lý Trực tiếp<br>Line Manager</th>
                                        <th>
                                            <div class="form-group">
                                                <textarea {{ auth()->user()->role != 'lm' ? 'disabled' : '' }} name="action_lm" class="form-control">{{ $recommendations['lm']['action'] ?? '' }}</textarea>
                                            </div>
                                        </th>
                                        <td class="w-10">
                                            <div class="signature-box">
                                                <!-- Chỗ để ký tên -->
                                            </div>
                                            <div class="form-group">
                                                <label for="lm_signature_date">Ngày ký / Date:</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Propose next step</th>
                                        <td colspan="2">
                                            <div class="checkbox-group">
                                                <label>
                                                    <input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }}
                                                        type="radio" name="lm_recommendation" value="highly_recommend"
                                                        {{ isset($recommendations['lm']) && $recommendations['lm']['propose_next_step'] == 'highly_recommend' ? 'checked' : '' }}>
                                                    <span>Highly recommend</span>
                                                </label>
                                                <label>
                                                    <input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }}
                                                        type="radio" name="lm_recommendation" value="recommend"
                                                        {{ isset($recommendations['lm']) && $recommendations['lm']['propose_next_step'] == 'recommend' ? 'checked' : '' }}>
                                                    <span>Recommend</span>
                                                </label>
                                                <label>
                                                    <input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }}
                                                        type="radio" name="lm_recommendation" value="do_not_recommend"
                                                        {{ isset($recommendations['lm']) && $recommendations['lm']['propose_next_step'] == 'do_not_recommend' ? 'checked' : '' }}>
                                                    <span>Do not recommend</span>
                                                </label>
                                                <label>
                                                    <input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }}
                                                        type="radio" name="lm_recommendation" value="hold_consider"
                                                        {{ isset($recommendations['lm']) && $recommendations['lm']['propose_next_step'] == 'hold_consider' ? 'checked' : '' }}>
                                                    <span>Hold Consider</span>
                                                </label>
                                                <label>
                                                    <input {{ auth()->user()->role != 'lm' ? 'disabled' : '' }}
                                                        type="radio" name="lm_recommendation" value="other_position"
                                                        {{ isset($recommendations['lm']) && $recommendations['lm']['propose_next_step'] == 'other_position' ? 'checked' : '' }}>
                                                    <span>Other position</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @if (auth()->user()->role == 'final')
                                    <tr>
                                        <th>Trưởng Bộ phận / Trưởng Phòng/ Giám Đốc Khối / Ban Giám Đốc<br>Head of
                                            Department /
                                            Head of Division / CEO</th>
                                        <th>
                                            <div class="form-group">
                                                <textarea name="action_final" class="form-control">{{ $recommendations['final']['action'] ?? '' }}</textarea>
                                            </div>
                                        </th>
                                        <td class="w-10">
                                            <div class="signature-box">
                                                <!-- Chỗ để ký tên -->
                                            </div>
                                            <div class="form-group">
                                                <label for="final_signature_date">Ngày ký / Date:</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Propose next step</th>
                                        <td colspan="2">
                                            <div class="checkbox-group">
                                                <label>
                                                    <input type="radio" name="final_recommendation"
                                                        value="highly_recommend"
                                                        {{ isset($recommendations['final']) && $recommendations['final']['propose_next_step'] == 'highly_recommend' ? 'checked' : '' }}>
                                                    <span>Highly recommend</span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="final_recommendation" value="recommend"
                                                        {{ isset($recommendations['final']) && $recommendations['final']['propose_next_step'] == 'recommend' ? 'checked' : '' }}>
                                                    <span>Recommend</span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="final_recommendation"
                                                        value="do_not_recommend"
                                                        {{ isset($recommendations['final']) && $recommendations['final']['propose_next_step'] == 'do_not_recommend' ? 'checked' : '' }}>
                                                    <span>Do not recommend</span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="final_recommendation"
                                                        value="hold_consider"
                                                        {{ isset($recommendations['final']) && $recommendations['final']['propose_next_step'] == 'hold_consider' ? 'checked' : '' }}>
                                                    <span>Hold Consider</span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="final_recommendation"
                                                        value="other_position"
                                                        {{ isset($recommendations['final']) && $recommendations['final']['propose_next_step'] == 'other_position' ? 'checked' : '' }}>
                                                    <span>Other position</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            </table>

                            <div class="form-group mb-4">
                                <label for="reference_feedback">Phản hồi về thông tin tham khảo (thực hiện bởi
                                    NS/TBP) / Reference feedback (Conducted by HR/ LM)</label>
                                <textarea {{ auth()->user()->role == 'final' ? 'disabled' : '' }} name="reference_feedback" id="reference_feedback" class="form-control" rows="4">{{ $candidate->reference_feedback ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i> Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-admin')
    <script>
        // Kiểm tra nhập số trong khoảng 1-10 cho các ô đánh giá
        document.querySelectorAll('.rating-input').forEach(input => {
            input.addEventListener('input', function() {
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > 10) {
                    this.value = 10;
                }
            });
        });

        // Tính tổng điểm và điểm trung bình
        function calculateScores() {
            // Xác định vai trò người dùng hiện tại
            const userRole = '{{ auth()->user()->role }}';

            // Thu thập tất cả điểm HR - tất cả vai trò đều có thể thấy điểm HR
            let hrScores = document.querySelectorAll('[name^="hr_rating_"]');
            let hrTotal = 0;
            let hrCount = 0;
            hrScores.forEach(input => {
                if (input.value && !isNaN(parseInt(input.value))) {
                    hrTotal += parseInt(input.value);
                    hrCount++;
                }
            });

            // Cập nhật tổng điểm HR
            const hrTotalField = document.querySelector('[name="hr_total_score"]');
            if (hrTotalField) {
                hrTotalField.value = hrTotal;
            }

            let lmTotal = 0;
            let lmCount = 0;

            // Chỉ LM và Final mới có thể thấy điểm LM
            if (userRole === 'lm' || userRole === 'admin') {
                let lmScores = document.querySelectorAll('[name^="lm_rating_"]');
                lmScores.forEach(input => {
                    if (input.value && !isNaN(parseInt(input.value))) {
                        lmTotal += parseInt(input.value);
                        lmCount++;
                    }
                });

                // Cập nhật tổng điểm LM nếu có field
                const lmTotalField = document.querySelector('[name="lm_total_score"]');
                if (lmTotalField) {
                    lmTotalField.value = lmTotal;
                }
            }

            let finalTotal = 0;
            let finalCount = 0;

            // Chỉ Final mới có thể thấy điểm Final
            if (userRole === 'final' || userRole === 'admin') {
                let finalScores = document.querySelectorAll('[name^="final_rating_"]');
                finalScores.forEach(input => {
                    if (input.value && !isNaN(parseInt(input.value))) {
                        finalTotal += parseInt(input.value);
                        finalCount++;
                    }
                });

                // Cập nhật tổng điểm Final nếu có field
                const finalTotalField = document.querySelector('[name="final_total_score"]');
                if (finalTotalField) {
                    finalTotalField.value = finalTotal;
                }
            }

            // Tính điểm trung bình dựa trên vai trò
            let totalScore = hrTotal;
            let validScores = (hrCount > 0) ? 1 : 0;

            // Tùy theo vai trò mà tính thêm điểm LM và/hoặc Final
            if (userRole === 'lm' || userRole === 'admin') {
                if (lmTotal > 0) {
                    totalScore += lmTotal;
                    validScores++;
                }
            }

            if (userRole === 'final' || userRole === 'admin') {
                if (finalTotal > 0) {
                    totalScore += finalTotal;
                    validScores++;
                }
            }

            const averageField = document.querySelector('[name="average_score"]');
            if (averageField) {
                if (validScores > 0) {
                    const average = Math.round(totalScore / validScores);
                    averageField.value = average;

                    // Hiển thị đánh giá dựa trên điểm trung bình
                    document.getElementById('unsatisfactory-badge').classList.add('d-none');
                    document.getElementById('good-badge').classList.add('d-none');
                    document.getElementById('excellent-badge').classList.add('d-none');

                    if (average >= 1 && average <= 52) {
                        document.getElementById('unsatisfactory-badge').classList.remove('d-none');
                    } else if (average >= 53 && average <= 104) {
                        document.getElementById('good-badge').classList.remove('d-none');
                    } else if (average >= 105) {
                        document.getElementById('excellent-badge').classList.remove('d-none');
                    }
                } else {
                    averageField.value = '0';
                }
            }
        }

        // Gắn sự kiện cho tất cả các trường nhập điểm
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('.rating-input');
            ratingInputs.forEach(input => {
                input.addEventListener('input', calculateScores);
            });

            // Tính điểm ban đầu nếu có dữ liệu
            calculateScores();
        });
    </script>
@endpush


@push('styles-admin')
@endpush
