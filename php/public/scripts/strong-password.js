const PasswordValidator = (function() {
    const commonPasswords = [
        "password", "123456", "qwerty", "admin", "welcome", 
        "password123", "abc123", "letmein", "monkey", "1234567890",
        "12345", "123456789", "iloveyou", "sunshine", "princess",
        "admin123", "welcome123", "login", "qwerty123", "dragon",
        "football", "baseball", "welcome", "adobe123", "111111",
        "1234", "123123", "696969", "qwertyuiop", "master", ""
    ];

    /**
     * Initialize password validation on an input element with the given options
     * @param {Object} config 
     */
    function init(config) {
        const defaultConfig = {
            passwordInputId: 'password',           
            meterContainerId: 'password-strength', 
            requirementsContainerId: 'password-requirements', 
            submitButtonId: 'submit-button',       
            showPasswordToggleId: 'show-password', 
            minStrength: 60,                       
            requirements: {                        
                length: true,                      
                uppercase: true,                   
                lowercase: true,                   
                number: true,                      
                special: true,                     
                common: true                       
            }
        };
        
        const options = {...defaultConfig, ...config};
        
        const passwordInput = document.getElementById(options.passwordInputId);
        const strengthContainer = document.getElementById(options.meterContainerId);
        const requirementsContainer = document.getElementById(options.requirementsContainerId);
        const submitButton = options.submitButtonId ? document.getElementById(options.submitButtonId) : null;
        const showPasswordToggle = options.showPasswordToggleId ? document.getElementById(options.showPasswordToggleId) : null;
        
        if (strengthContainer && !strengthContainer.querySelector('.strength-meter')) {
            createStrengthMeterElements(strengthContainer);
        }
        
        if (requirementsContainer && !requirementsContainer.querySelector('ul')) {
            createRequirementsListElements(requirementsContainer, options.requirements);
        }
        
        if (passwordInput) {
            passwordInput.addEventListener('keyup', function() {
                checkPasswordStrength(this.value, options);
            });
            if (passwordInput.value) {
                checkPasswordStrength(passwordInput.value, options);
            }
        }
        
        if (showPasswordToggle && passwordInput) {
            showPasswordToggle.addEventListener('click', function() {
                passwordInput.type = this.checked ? 'text' : 'password';
            });
        }
    }
    
    /**
     * Create strength meter DOM elements
     * @param {HTMLElement} container 
     */
    function createStrengthMeterElements(container) {
        const meterHtml = `
            <div class="strength-meter">
                <div class="strength-meter-fill" id="strength-meter-fill"></div>
            </div>
            <div class="password-strength-text" id="password-strength-text">Password strength</div>
        `;
        container.innerHTML = meterHtml;
    }
    
    /**
     * Create requirements list DOM elements
     * @param {HTMLElement} container 
     * @param {Object} requirements 
     */
    function createRequirementsListElements(container, requirements) {
        let listItems = '';
        
        if (requirements.length) {
            listItems += '<li id="length-check"><span class="check-icon">❌</span> At least 8 characters</li>';
        }
        if (requirements.uppercase) {
            listItems += '<li id="uppercase-check"><span class="check-icon">❌</span> At least one uppercase letter</li>';
        }
        if (requirements.lowercase) {
            listItems += '<li id="lowercase-check"><span class="check-icon">❌</span> At least one lowercase letter</li>';
        }
        if (requirements.number) {
            listItems += '<li id="number-check"><span class="check-icon">❌</span> At least one number</li>';
        }
        if (requirements.special) {
            listItems += '<li id="special-check"><span class="check-icon">❌</span> At least one special character</li>';
        }
        if (requirements.common) {
            listItems += '<li id="common-check"><span class="check-icon">❌</span> Not a commonly used password</li>';
        }
        
        const requirementsHtml = `
            <p>Your password should have:</p>
            <ul>${listItems}</ul>
        `;
        
        container.innerHTML = requirementsHtml;
    }
    
    /**
     * Check password strength and update UI elements
     * @param {string} password 
     * @param {Object} options 
     */
    function checkPasswordStrength(password, options) {
        const strengthMeter = document.getElementById('strength-meter-fill');
        const strengthText = document.getElementById('password-strength-text');
        
        if (!strengthMeter || !strengthText) return;
        
        if (options.requirements.length) {
            const lengthCheck = document.getElementById('length-check');
            const hasLength = password.length >= 8;
            updateCheckMark(lengthCheck, hasLength);
        }
        
        if (options.requirements.uppercase) {
            const uppercaseCheck = document.getElementById('uppercase-check');
            const hasUppercase = /[A-Z]/.test(password);
            updateCheckMark(uppercaseCheck, hasUppercase);
        }
        
        if (options.requirements.lowercase) {
            const lowercaseCheck = document.getElementById('lowercase-check');
            const hasLowercase = /[a-z]/.test(password);
            updateCheckMark(lowercaseCheck, hasLowercase);
        }
        
        if (options.requirements.number) {
            const numberCheck = document.getElementById('number-check');
            const hasNumber = /[0-9]/.test(password);
            updateCheckMark(numberCheck, hasNumber);
        }
        
        if (options.requirements.special) {
            const specialCheck = document.getElementById('special-check');
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            updateCheckMark(specialCheck, hasSpecial);
        }
        
        if (options.requirements.common) {
            const commonCheck = document.getElementById('common-check');
            const isCommon = commonPasswords.includes(password.toLowerCase());
            updateCheckMark(commonCheck, !isCommon);
        }
        
        let strengthScore = 0;
        
        if (password.length === 0) {
            strengthScore = 0;
            strengthMeter.style.width = '0%';
            strengthMeter.className = 'strength-meter-fill';
            strengthText.textContent = 'Password strength';
            
            if (options.submitButtonId) {
                const submitButton = document.getElementById(options.submitButtonId);
                if (submitButton) submitButton.disabled = true;
            }
            
            return;
        }
        
        strengthScore += Math.min(25, password.length * 3);
        
        if (/[A-Z]/.test(password)) strengthScore += 15; // Uppercase
        if (/[a-z]/.test(password)) strengthScore += 10; // Lowercase
        if (/[0-9]/.test(password)) strengthScore += 15; // Numbers
        if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strengthScore += 20; // Special chars
        
        if (commonPasswords.includes(password.toLowerCase())) strengthScore = Math.min(strengthScore, 20);
        
        strengthScore = Math.min(100, strengthScore);
        
        strengthMeter.style.width = strengthScore + '%';
        
        if (strengthScore < 20) {
            strengthMeter.className = 'strength-meter-fill very-weak';
            strengthText.textContent = 'Very weak';
        } else if (strengthScore < 40) {
            strengthMeter.className = 'strength-meter-fill weak';
            strengthText.textContent = 'Weak';
        } else if (strengthScore < 60) {
            strengthMeter.className = 'strength-meter-fill medium';
            strengthText.textContent = 'Medium';
        } else if (strengthScore < 80) {
            strengthMeter.className = 'strength-meter-fill strong';
            strengthText.textContent = 'Strong';
        } else {
            strengthMeter.className = 'strength-meter-fill very-strong';
            strengthText.textContent = 'Very strong';
        }
        
        if (options.submitButtonId) {
            const submitButton = document.getElementById(options.submitButtonId);
            if (submitButton) {
                submitButton.disabled = strengthScore < options.minStrength;
            }
        }
        
        return {
            score: strengthScore,
            level: strengthText.textContent.toLowerCase(),
            meetsMinimumRequirements: strengthScore >= options.minStrength
        };
    }
    
    /**
     * @param {HTMLElement} element 
     * @param {boolean} isValid 
     */
    function updateCheckMark(element, isValid) {
        if (!element) return;
        
        const checkIcon = element.querySelector('.check-icon');
        if (checkIcon) {
            checkIcon.textContent = isValid ? '✅' : '❌';
            element.style.color = isValid ? '#4caf50' : '#f44336';
        }
    }
    
    return {
        init: init,
        checkPassword: function(password) {
            let score = 0;
            
            if (password.length === 0) return { score: 0, level: 'empty' };
            
            score += Math.min(25, password.length * 3);
            
            if (/[A-Z]/.test(password)) score += 15;
            if (/[a-z]/.test(password)) score += 10;
            if (/[0-9]/.test(password)) score += 15;
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) score += 20;
            
            if (commonPasswords.includes(password.toLowerCase())) score = Math.min(score, 20);
            
            score = Math.min(100, score);
            
            let level = 'very weak';
            if (score >= 20 && score < 40) level = 'weak';
            else if (score >= 40 && score < 60) level = 'medium';
            else if (score >= 60 && score < 80) level = 'strong';
            else if (score >= 80) level = 'very strong';
            
            return { score, level };
        },
        isCommonPassword: function(password) {
            return commonPasswords.includes(password.toLowerCase());
        }
    };
})();