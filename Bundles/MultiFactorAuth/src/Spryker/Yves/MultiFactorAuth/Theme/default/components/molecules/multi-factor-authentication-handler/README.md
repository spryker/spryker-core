# Multi Factor Authentication Handler

## Description
Handles the multi-factor authentication process for user verification. This component manages the interaction flow for factor activation and verification.

## Integration
- Integrated with the customer profile page
- Used in the MFA activation flow
- Handles verification code submission and validation

## Usage
```twig
{% include molecule('multi-factor-authentication-handler') with {
    data: {
        form: form
    }
} only %}
```
