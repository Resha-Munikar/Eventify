@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')

<!-- Toast alerts -->
<div class="w-full max-w-md mx-auto pt-4 px-4">
    @if(session('warning'))
        <div class="flex items-center p-4 mb-4 text-amber-800 bg-amber-50 border border-amber-200 rounded-xl dark:bg-gray-800 dark:text-amber-300 dark:border-amber-800 shadow-sm" role="alert">
            <iconify-icon icon="solar:danger-triangle-bold" class="text-xl shrink-0 mr-3 text-amber-500"></iconify-icon>
            <div class="text-xs sm:text-sm font-medium">{{ session('warning') }}</div>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="flex items-center p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl dark:bg-gray-800 dark:text-rose-300 dark:border-rose-800 shadow-sm" role="alert">
                <iconify-icon icon="solar:close-circle-bold" class="text-xl shrink-0 mr-3 text-rose-500"></iconify-icon>
                <div class="text-xs sm:text-sm font-medium">{{ $error }}</div>
            </div>
        @endforeach
    @endif
</div>

<!-- OTP Verification Container -->
<div class="bg-[#FFF8F0] dark:bg-gray-800 min-h-[calc(100vh-180px)] flex justify-center items-start pt-6 pb-12 px-4">
    <div class="bg-white dark:bg-gray-700 shadow-xl rounded-2xl overflow-hidden w-full max-w-[440px] flex flex-col border border-gray-100 dark:border-gray-600">
        
        <div class="p-6 sm:p-8 flex flex-col items-center text-center">
            
            <!-- Icon Badge -->
            <div class="w-16 h-16 rounded-2xl bg-[#8D85EC]/15 dark:bg-[#8D85EC]/25 flex items-center justify-center text-[#8D85EC] mb-4 shadow-inner">
                <iconify-icon icon="solar:shield-check-bold" class="text-3xl"></iconify-icon>
            </div>

            <!-- Heading & Explanation -->
            <h2 class="text-2xl sm:text-3xl font-bold text-[#8d85ec] mb-2">Verify Your Email</h2>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                Enter the 6-digit verification code sent to <br>
                <span class="font-semibold text-gray-900 dark:text-white bg-purple-50 dark:bg-gray-800 px-2.5 py-1 rounded-lg inline-block mt-1 border border-purple-100 dark:border-gray-600">{{ $maskedEmail }}</span>
            </p>

            <div class="w-full bg-purple-50/70 dark:bg-gray-800/70 border border-purple-100 dark:border-gray-600 rounded-xl p-3 mb-6 flex items-center justify-center gap-2 text-xs text-purple-700 dark:text-purple-300">
                <iconify-icon icon="solar:clock-circle-bold" class="text-base"></iconify-icon>
                <span>This code will expire in <strong>5 minutes</strong>.</span>
            </div>

            <!-- OTP Verification Form -->
            <form action="{{ route('verification.verify') }}" method="POST" id="otp-form" class="w-full space-y-5">
                @csrf

                <!-- Combined hidden/fallback input -->
                <input type="hidden" name="otp" id="combined-otp" value="{{ old('otp') }}">

                <!-- 6 Individual Digit Boxes -->
                <div>
                    <div class="flex justify-between gap-2 sm:gap-2.5" id="otp-digit-inputs">
                        @for($i = 0; $i < 6; $i++)
                            <input 
                                type="text" 
                                inputmode="numeric" 
                                pattern="[0-9]*" 
                                maxlength="1" 
                                data-index="{{ $i }}"
                                class="otp-box w-11 h-13 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-bold rounded-xl border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white outline-none focus:border-[#8D85EC] focus:ring-2 focus:ring-[#8D85EC]/30 transition shadow-xs"
                                autocomplete="off"
                                required
                            />
                        @endfor
                    </div>
                </div>

                <!-- Verify Button -->
                <button type="submit" 
                        id="verify-submit-btn"
                        class="w-full py-3 rounded-xl font-bold text-white bg-[#8D85EC] hover:bg-[#7b76e4] transition transform active:scale-[0.98] shadow-md shadow-[#8D85EC]/25 text-sm sm:text-base flex items-center justify-center gap-2">
                    <iconify-icon icon="solar:check-read-bold" class="text-lg"></iconify-icon>
                    <span>Verify Code</span>
                </button>
            </form>

            <!-- Resend OTP Form -->
            <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-600 w-full flex flex-col items-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Didn't receive the code?</p>
                
                <form action="{{ route('verification.resend') }}" method="POST" id="resend-form">
                    @csrf
                    <button type="submit" 
                            id="resend-btn" 
                            class="text-xs sm:text-sm font-bold text-[#8D85EC] hover:text-[#746cd4] hover:underline transition disabled:opacity-50 disabled:cursor-not-allowed disabled:no-underline flex items-center gap-1.5"
                            {{ ($cooldownRemaining > 0) ? 'disabled' : '' }}>
                        <iconify-icon icon="solar:restart-bold" class="text-sm"></iconify-icon>
                        <span id="resend-btn-text">
                            @if($cooldownRemaining > 0)
                                Resend code in {{ $cooldownRemaining }}s
                            @else
                                Resend Verification Code
                            @endif
                        </span>
                    </button>
                </form>
            </div>

            <!-- Footer switch account link -->
            <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                <span>Wrong account?</span>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-rose-500 font-semibold hover:underline ml-1">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-rose-500 font-semibold hover:underline ml-1">Log in with another email</a>
                @endauth
            </div>

        </div>
    </div>
</div>

<!-- OTP Interactive Behavior Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const boxes = document.querySelectorAll('.otp-box');
    const combinedInput = document.getElementById('combined-otp');
    const form = document.getElementById('otp-form');
    const resendBtn = document.getElementById('resend-btn');
    const resendText = document.getElementById('resend-btn-text');

    let cooldownSeconds = parseInt("{{ $cooldownRemaining ?? 0 }}", 10) || 0;

    // Prefill old input if present
    if (combinedInput.value && combinedInput.value.length === 6) {
        combinedInput.value.split('').forEach((char, i) => {
            if (boxes[i]) boxes[i].value = char;
        });
    }

    // Auto-focus first empty box
    const firstEmpty = Array.from(boxes).find(b => !b.value) || boxes[0];
    if (firstEmpty) firstEmpty.focus();

    function updateCombined() {
        let code = '';
        boxes.forEach(b => { code += b.value; });
        combinedInput.value = code;
    }

    boxes.forEach((box, index) => {
        // Handle single digit input and advance
        box.addEventListener('input', (e) => {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val ? val.slice(-1) : '';

            updateCombined();

            if (e.target.value && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }

            // Auto-submit if all 6 digits entered
            if (combinedInput.value.length === 6) {
                form.submit();
            }
        });

        // Handle Backspace navigation
        box.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !box.value && index > 0) {
                boxes[index - 1].focus();
            }
        });

        // Handle Paste of full 6-digit code
        box.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
            if (pastedData.length >= 6) {
                for (let i = 0; i < 6; i++) {
                    if (boxes[i]) boxes[i].value = pastedData[i];
                }
                updateCombined();
                boxes[5].focus();
                form.submit();
            } else if (pastedData.length > 0) {
                for (let i = 0; i < pastedData.length && (index + i) < 6; i++) {
                    boxes[index + i].value = pastedData[i];
                }
                updateCombined();
                const nextFocus = Math.min(5, index + pastedData.length);
                boxes[nextFocus].focus();
            }
        });
    });

    // Handle Resend Cooldown Countdown
    if (cooldownSeconds > 0) {
        const timer = setInterval(() => {
            cooldownSeconds--;
            if (cooldownSeconds <= 0) {
                clearInterval(timer);
                resendBtn.removeAttribute('disabled');
                resendText.textContent = 'Resend Verification Code';
            } else {
                resendText.textContent = `Resend code in ${cooldownSeconds}s`;
            }
        }, 1000);
    }
});
</script>

@endsection
