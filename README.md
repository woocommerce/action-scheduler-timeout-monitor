## WooCommerce Subscriptions Action Scheduler Timeout Monitor

If a scheduled action runs for more than its allocated time (`action_scheduler_failure_period`) the `ActionScheduler_QueueCleaner` will mark the action as failed and log the nondescript error `action timed out after 300 seconds`. Because this cleaning event occurs in another PHP instance and separate process there's no stack trace which can be included as part of this error.

This mini-extension keeps a record of where the action got caught up to help troubleshoot what's taking the action more than the 5 minutes (default) allowed.

This plugin makes use of the resource intensive WP `'all'` action and therefore it is not recommended to have this plugin active for any longer than necessary.

### Installation

1. Upload the plugin's files to the `/wp-content/plugins/` directory of your WordPress site
1. Activate the plugin through the **Plugins** menu in WordPress

### Requirements

In order to use the extension, you will need:

* WooCommerce Subscriptions. Recommended v2.2.17+
* WooCommerce

#### License

This plugin is released under [GNU General Public License v3.0](http://www.gnu.org/licenses/gpl-3.0.html).

---

<p align="center">
<img src="https://cloud.githubusercontent.com/assets/235523/11986380/bb6a0958-a983-11e5-8e9b-b9781d37c64a.png" width="160">
</p>
