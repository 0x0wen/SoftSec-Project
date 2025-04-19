<link rel="stylesheet" href="/styles/strong-password.css">
<script src="/scripts/strong-password.js"></script>

<main class="main">
    <section class="signup">
        <!-- Title -->
        <h1 class="signup__title">Sign Up</h1>
        <!-- Form -->
        <form class="form" action="/auth/sign-up/company" method="POST">
            <!-- Nama -->
            <div class="form__group">
                <label for="name" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['name'])):  ?>
                        <?= htmlspecialchars($errorFields['name'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                ">
                    Name
                </label>

                <input
                    name="name"
                    type="text"
                    placeholder="Enter your name"
                    value="<?= htmlspecialchars($fields['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="input" />

                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['name'])):  ?>
                        <?= htmlspecialchars($errorFields['name'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Email -->
            <div class="form__group">
                <label for="email" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['email'])):  ?>
                        <?= htmlspecialchars($errorFields['email'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                ">
                    Email
                </label>

                <input
                    name="email"
                    type="text"
                    placeholder="Enter your email"
                    value="<?= htmlspecialchars($fields['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="input" />

                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['email'])):  ?>
                        <?= htmlspecialchars($errorFields['email'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Password -->
            <div class="form__group">
            <label for="password" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['password'])):  ?>
                        <?= htmlspecialchars($errorFields['password'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                ">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Enter your password"
                    class="input" 
                    value="<?= htmlspecialchars($fields['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    />
                
                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['password'])):  ?>
                        <?= htmlspecialchars($errorFields['password'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
                    
                <!-- Password strength meter container -->
                <div id="password-strength" class="password-strength-meter"></div>
                
                <!-- Password requirements checklist container -->
                <div id="password-requirements" class="password-requirements"></div>
            </div>

            <!-- Show Password Toggle -->
            <div class="form__group form__group--checkbox">
                <input type="checkbox" id="show-password">
                <label for="show-password">Show password</label>
            </div>


            <!-- Location -->
            <div class="form__group">
                <label for="location" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['location'])):  ?>
                        <?= htmlspecialchars($errorFields['location'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                ">
                    Location
                </label>
                <input
                    name="location"
                    type="location"
                    placeholder="Enter your company location"
                    value="<?= htmlspecialchars($fields['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="input" />
                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['location'])):  ?>
                        <?= htmlspecialchars($errorFields['location'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- About -->
            <div class="form__group">
                <label for="about" class="form__label 
                    <?php if (isset($errorFields) && isset($errorFields['about'])):  ?>
                        <?= htmlspecialchars($errorFields['about'][0] ? 'form__error-message' : '', ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                ">
                    About
                </label>
                <textarea
                    name="about"
                    placeholder="Tell us about your company"
                    class="textarea"><?= htmlspecialchars($fields['about'] ?? '') ?></textarea>
                <p class="form__error-message">
                    <?php if (isset($errorFields) && isset($errorFields['about'])):  ?>
                        <?= htmlspecialchars($errorFields['about'][0] ?? '') ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- submit button -->
            <button type="submit" id="submit-button" class="button button--default-size button--default-color">
                Register Your Company
            </button>
        </form>

        <!-- Sign up -->
        <p class="signup__register-prompt">
            Already sign up your company?
            <a href="/auth/sign-in" class="anchor-link">
                Sign in
            </a>
        </p>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize with default options
        PasswordValidator.init({
            passwordInputId: 'password',
            meterContainerId: 'password-strength',
            requirementsContainerId: 'password-requirements',
            submitButtonId: 'submit-button',
            showPasswordToggleId: 'show-password',
            minStrength: 80
        });
    });
</script>