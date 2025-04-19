<main class="main">
    <section class="signin">
        <!-- Title -->
        <h1 class="signin__title">Sign In</h1>

        <?php if (isset($rateLimited) && $rateLimited): ?>
            <!-- Rate Limit Warning -->
            <div class="rate-limit-warning">
                <p>Your account is temporarily locked due to too many failed login attempts.</p>
                <p>Please try again after <?= htmlspecialchars($lockoutTimeRemaining ?? ''); ?></p>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form class="form" action="/auth/sign-in" method="POST">
            <!-- Email -->
            <div class="form__group">
                <label for="email" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['email'])): ?>
                        <?= htmlspecialchars($errorFields['email'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>
                ">
                    Email
                </label>

                <input name="email" type="text" placeholder="Enter your email"
                    value="<?= htmlspecialchars($fields['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="input"
                    <?= (isset($rateLimited) && $rateLimited) ? 'disabled' : ''; ?> />

                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['email'])): ?>
                        <?= htmlspecialchars($errorFields['email'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Password -->
            <div class="form__group">
                <label for="password" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['password'])): ?>
                        <?= htmlspecialchars($errorFields['password'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>
                ">
                    Password
                </label>
                <input name="password" type="password" placeholder="Enter your password"
                    value="<?= htmlspecialchars($fields['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="input"
                    <?= (isset($rateLimited) && $rateLimited) ? 'disabled' : ''; ?> />
                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['password'])): ?>
                        <?= htmlspecialchars($errorFields['password'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <?php if (isset($remainingAttempts) && $remainingAttempts < 5 && !isset($rateLimited)): ?>
                <!-- Remaining attempts info -->
                <div class="attempts-info">
                    <p>You have <?= htmlspecialchars($remainingAttempts) ?> login attempts remaining.</p>
                </div>
            <?php endif; ?>

            <button type="submit" class="button button--default-size button--default-color" <?= (isset($rateLimited) && $rateLimited) ? 'disabled' : ''; ?>>
                Sign In
            </button>
        </form>

        <!-- Sign up -->
        <p class="signin__register-prompt">
            Don't have an account?
            <a href="/auth/sign-up" class="anchor-link">
                Sign up
            </a>
        </p>
    </section>
</main>

<style>
    .rate-limit-warning {
        background-color: #fff3cd;
        color: #856404;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ffeeba;
    }

    .attempts-info {
        font-size: 0.875rem;
        color: #dc3545;
        margin-bottom: 1rem;
    }

    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    input:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if there's a rate limit warning
        const rateLimitWarning = document.querySelector('.rate-limit-warning');

        if (rateLimitWarning) {
            // Parse the time remaining text to get seconds
            const timeText = rateLimitWarning.querySelector('p:last-child').textContent;
            let remainingTime = 0;

            // Extract numeric values from time text
            if (timeText.includes('seconds')) {
                remainingTime = parseInt(timeText.match(/(\d+) seconds/)[1]);
            } else if (timeText.includes('minute')) {
                const minutes = parseInt(timeText.match(/(\d+) minute/)[1]);
                remainingTime = minutes * 60;
            } else if (timeText.includes('hour')) {
                const hours = parseInt(timeText.match(/(\d+) hour/)[1]);
                remainingTime = hours * 3600;
            }

            if (remainingTime > 0) {
                // Start countdown
                const countdownInterval = setInterval(function () {
                    remainingTime--;

                    if (remainingTime <= 0) {
                        // Time's up - reload the page
                        clearInterval(countdownInterval);
                        window.location.reload();
                        return;
                    }

                    // Format the remaining time
                    let formattedTime = '';
                    if (remainingTime < 60) {
                        formattedTime = remainingTime + ' seconds';
                    } else if (remainingTime < 3600) {
                        const minutes = Math.ceil(remainingTime / 60);
                        formattedTime = minutes + ' minute' + (minutes > 1 ? 's' : '');
                    } else {
                        const hours = Math.ceil(remainingTime / 3600);
                        formattedTime = hours + ' hour' + (hours > 1 ? 's' : '');
                    }

                    // Update the countdown text
                    const counterElem = rateLimitWarning.querySelector('p:last-child');
                    counterElem.textContent = 'Please try again after ' + formattedTime;

                }, 1000);
            }
        }
    });
</script>