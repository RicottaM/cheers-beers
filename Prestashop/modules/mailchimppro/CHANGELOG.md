## Multi-store-cron-mixture-fix (2023-11-22)

*  Quick fix for store default country 
*  skip merge validation for newsletter module subscribers 
*  not set cover image fix 
*  multistore fix for job based sync 
*  version update 
*  build process 
*  build timeout 
*  download off 
*  formatter store id-s 
*  cronjob and BO sync multistore 
*  reserve job 
*  cronjob and BO sync multistore 
*  php cronjob block for multi stores 
*  php cronjob block for muulti store BO message + new validation requirements for Addons 
*  version bump, debug removed 


## v3.0.8 (2023-10-16)

*  first commit v3 
*  build it on push v3 
*  specific price changes 
*  simplified if statements 
*  no escape needed 
*  no package annotation test 
*  translations validator fix 
*  translation fix 
*  copyright texts 
*  image format and no escape 
*  missing {literal} 
*  keep mailchimp subscriptions status 
*  adding cronjob based sync feature to hooks 
*  executeS change to execute 
*  executeS to execute 
*  adding cronjob based sync feature to hooks - finished 
*  Class not found error fix 
*  define hookModuleRoutes 
*  drop mailchimp specific table on uninstall 
*  small fixes 
*  fix for ordersync command data types 
*  v3.0.1 upgrade 
*  build 
*  language iso code correction 
*  v2.0.16 
*  older ps_Emailsubscription cmopatibility 
*  older newsletter module compatibility 
*  Newsletter subscribers job correction to be unique 
*  language iso_code mapping 
*  version increase 
*  product extra hook fix 
*  required merge fields 
*  required merge fields 
*  fix country iso in address merge field 
*  version update 
*  deploy need to be resolved 
*  order and cart currency fix 
*  customer and order sync commands filter 
*  currency iso code compatibility PS173 
*  added customer sync filters for hooks 
*  added customer filter to cart sync 
*  cart save action fix 
*  country code in address formatter 
*  upgrade function 3.0.6 
*  subscription status - check if front or admin change 
*  cronjob log moved to module/logs folder from cache directory 
*  wget cron job added to config section modifications 
*  campaign id and address USA fix + cron fix for hook based option 
*  version increase 
*  campaign association based on landing_site parameter fix 


## v2.0.16-lang_iso (2023-04-06)

*  language iso code correction 
*  v2.0.16 


## v2.0.14 (2022-12-01)

*  PS 1.6 newsletter 
*  Newsletter sync fix - only wizard 
*  Newsletter fix 
*  Version increase 


## v2.0.12 (2022-11-08)

*  PS 1.6 newsletter lang 
*  tools usage 
*  specific price product page + product update 
*  upgrade function 
*  campaign setcookie fix 
*  v2.0.12 


## V2.0.11 (2022-10-24)

*  master build 
*  newsletter subs on sync 
*  newsletter subs on sync version 
*  version to 2.0.11 


## v2.0.0.10 (2022-04-27)

*  Fix description 
*  Quickfix 
*  Auto deploy to module-downloads.prestachamps.com 
*  validation fixes 
*  Fix newsletter registration 
*  Remove namespace use in main file 
*  build fix 
*  Fix verification stuff 
*  Bump version to 2.0.10 


## v2.0.9 (2022-02-23)

*  Add changelog creator 
*  Quick permission fix 
*  Permissions fix 
*  Permissions fix 
*  Quickfix 
*  Exclude unused files from distribution 
*  Use unshallow clone 
*  Version release 


## v2.0.8 (2022-02-17)

*  Fix method at customers 
*  Fix customer account update hook bug 
*  Make account update retro compatible with PS 16 
*  Add order IDs to the setup controller 
*  Implement order sync action in wizard controller 
*  Remove setup is completed message from places where is should not belong 
*  Add order sync functionality to the wizard 
*  Improve auth flow 
*  Add notification about not secure page to setup wizard 
*  Improve cart save hook 
*  Fix 500 error at customer update and create 
*  Remove sync button and list id change functionality 
*  Improve compatibility with new PS versions 
*  Fix list requires DOI 
*  Fix `use` error on PS 1.6 
*  Implement product sync from admin 
*  Customer login status fix 
*  Fix array access on bool value 
*  CSS Fixes 
*  Increment version number 
*  Create build action 
*  Rename .php_cs to .php-cs-fixer.php 
*  Delete .php-cs-fixer.php 
*  Create .php-cs-fixer.php 
*  Update main.yml 
*  Add console commands 
*  File mode changes 
*  Implement store prefix functionality 


## v2.0.5 (2019-09-10)

*  Only launch hooks when the API key is set 
*  Align long line in hookDisplayHeader 
*  Hide add new list button 
*  Set version number to 2.0.5 


## v2.0.3 (2019-07-09)

*  Basic implementation of Prestashop newsletter signup 
*  Implement cart sync command 
*  Implement cart sync command 
*  Sync customer carts for new guest accounts 
*  Sync customer carts for existing guest accounts 
*  Implement displayFooterBefore class 
*  Use FooterBefore class instead of inline hook logic 
*  Implement cart save hook 
*  Use CartSave class instead of inline hook 
*  Actually run the logic 
*  Bugfix 
*  Add PUT to supported methods 
*  Use proper class name when using cart sync 
*  Use PUT method for Customer Sync where is it possible 
*  Fix guest checkout account creation 
*  Hotfix 
*  Trigger DOI emails 
*  Improve subscriber status detection 
*  Fix config controller error when API key is not set 
*  Validate, validate, validate 
*  Remove unused index.php files 
*  Use use Tools::strtolower() instead of the built-in strtolower fn 


## v2.0.2 (2019-04-04)

*  A POC for using the oauth2 middleware 
*  Add output filtering to smarty templates 
*  Implement e-commerce data delete 
*  Show success message only when all requests are completed 
*  Fix ajaxq isQueueRunning method 
*  Fix ajaxq isQueueRunning method 
*  A POC for using the oauth2 middleware 
*  Implement proper origin check for onMessage event 
*  Implement proper origin check for onMessage event 
*  Fix promo code does not sync properly 
*  Syntax error fix 
*  Improve Oauth2 flow 
*  Implement API key check 
*  Add merge_fields to list member 
*  Add opt-in status to MC customer list view 
*  Properly trigger double opt in 
*  Finish middleware integration 
*  Upgrade toastr.css 
*  Release new version 
*  Fix changelog 


## v2.0.1 (2019-01-16)

*  Show the products synced success message ONLY after ALL products were synced 
*  Force ProductFormatter::sanitizeLanguageFieldToString for string 
*  Show meaningful error message @ OrderSync 
*  Add Changelog 
*  Add module key property to mailchimppro.php 
*  Change module name from MailchimpPro to Mailchimp 


## v2.0.0 (2018-12-14)

*  Rename cart rule formatter to Promo rule formatter 
*  Implement CartRuleSync command 
*  Add PromoCodes and PromoRules controller 
*  Add Promo rules menu item to the module menu 
*  Add promo codes to the order sync 
*  Implement promo code sync during setup 
*  Remove auto generated CHANGELOG 
*  Add module to moduleRoutes hook 
*  PrestaShop 1.6 compatibility fix 
*  Implement promoCode sync action in sync ctrl 
*  Implement promo code sync views 
*  Correct addons suggestions 
*  Increase version number 


