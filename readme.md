What is this?
===

It's an online tool I wrote to help me and some friends keep track of our poker games. I found it very difficult to coordinate poker games and keep track of who was hosting and all that stuff, so I made this to help make things better. 

It worked well, until we all developed a disinterest in playing poker.

Why is this here?
===

Since I've been working in Node.js for a while now, I don't have anything recent in PHP online, so I thought I'd toss this up. It's old (we stopped using it in late 2010, and it's based in CakePHP 1.3), but it does the job of demonstrating some skills all the same.

How it works
===

Users sign up, and when they do, an activiation email is sent to the sote owner to enable their account.

Once activated, users can add new games and view existing ones. They can also RSVP and leave comments.

If you want to make a user an admin user (so they can do all kinds of fun admin things), you'll need to set `admin` to 1 for their account in the DB directly.

Usage
===

You'll need to tie it to your own MySQL server my modifying the config/database.php file. Import poker.sql to your server to get started. You'll also need to make a change to the users_controller.php file so that user activation emails get sent to an address you have access to; see the __sendRegisteredEmail() function.

License
===

This is released under the [WTFPL](http://en.wikipedia.org/wiki/WTFPL)
