# TODO - Enhanced Magic Link Verification

## Completed Tasks
- [x] Create magic link verification popup modal
- [x] Add form fields: name, address, location, camera photo
- [x] Implement geolocation API integration
- [x] Implement camera API with auto-capture after 3 seconds
- [x] Add form validation (all fields required)
- [x] Modify handleMagicLink to show popup instead of direct login
- [x] Add verifyMagicLinkForm method to handle form submission
- [x] Add route for form verification
- [x] Add kickout mechanism for failed verification (10-second redirect to forgot-password)
- [x] Update login page with kickout countdown script
- [x] Include popup in forgot-password view
- [x] Add warning modal before final confirmation
- [x] Warning modal shows legal consequences and monitoring notice

## Testing Checklist
- [ ] Test magic link generation and display after code verification
- [ ] Test popup display when clicking magic link
- [ ] Test geolocation permission and capture
- [ ] Test camera permission and auto-capture
- [ ] Test form validation (submit disabled until all fields filled)
- [ ] Test warning modal appears on submit button click
- [ ] Test successful verification and login after accepting warning
- [ ] Test failed verification (incomplete form) redirects to login with 10-second countdown back to forgot-password page
- [ ] Test 10-second countdown and redirect to forgot-password
- [ ] Test browser compatibility (geolocation and camera APIs)

## Notes
- Photo is captured as base64 data URL and stored in hidden input
- Location uses navigator.geolocation with high accuracy
- Camera auto-captures after 3 seconds of opening
- All fields must be filled for submit button to enable
- Warning modal shows before final submission with legal consequences
- Kickout sets session flag and redirects to /login with error message
- Login page checks for kickout session and shows countdown overlay
