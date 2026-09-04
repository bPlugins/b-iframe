Email marketing
===============

Announcement email for iFrame, aimed at existing users of other bPlugins
plugins. Not shipped in the release zip.

  email.html          HTML version (600px, table based, inline styles,
                      safe for Gmail/Outlook). Before sending:
                        1. replace {{unsubscribe_url}} with your ESP's
                           merge tag
                        2. upload assets/*.png somewhere public and swap
                           the two src="assets/…" values for absolute
                           URLs (email clients cannot load relative
                           paths). The hero banner and icon already load
                           from ps.w.org.
  assets/             the two screenshots used inside the email
  email.txt           plain-text version with the suggested subject line
  subject-lines.txt   alternative subject lines to A/B test

Personalisation ideas before sending:
  - Swap "one of our plugins" for the recipient's actual plugin name
    if your ESP has that field (e.g. "you use Html5 Video Player").
  - Send from a personal address; the copy invites replies.
