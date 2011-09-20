# HOA Redirect

Wordpress plugin to redirect requests.

## Redirect Conditions

1. Request for front page of child blog from not logged in client
    * redirect to '/welcome/'
2. Login for users with role &lt; Editor
    * redirect to '/'
3. Login for users with role &gt;= Editor
    * do nothing (Wordpress automatically redirects to dashboard)

### Redirecting Front Page Requests

**Hook**: action  'wp'

If the install is multisite AND request is not for the root/mail blog AND the request is the front page AND user is not logged in. Redirect to '/welcome/' and then exit.

### Redirect after login

**Hook**: filter 'login_redirect' priority: 100 args: 3

If hook is not called with a logged in user (valid WP\_User object) do nothing.

If request redirect variable is not empty and does not contain wp-admin/ then return it

if user is a homeowner or board member {!has\_cap( 'publish\_posts' )} find their primary blog and redirect them to it's home page


## Issues

1. Requests to home page from users that have previously selected 'Remember Me' (have login cookie) are being redirected to the /welcome/ page instead of being left alone to go to the front page. ><