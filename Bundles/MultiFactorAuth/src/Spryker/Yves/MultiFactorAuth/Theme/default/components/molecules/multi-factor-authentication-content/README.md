# Multi Factor Authentication Content

## Description
Displays the content and UI elements for the multi-factor authentication process. This component provides the visual interface for users to interact with during MFA setup and verification.

## Integration
- Used within the MFA activation flow
- Displays verification code input fields
- Shows status messages and instructions

## Usage
```twig
{% include molecule('multi-factor-authentication-content') with {
    data: {
        form: form,
        isFactorActive: isFactorActive
    }
} only %}
```
