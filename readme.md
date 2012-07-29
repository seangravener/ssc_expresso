## Expresso store

Use this plugin to determine if a member has purchased a particular entry_id (product) from the Expresso Store.

Use redirect parameter to redirect member to the product page or error page.

	{exp:ssc_expstore:is_owner entry_id='{segment_3}' member_id='{member_id}' redirect='store/product/{segment_3}'}

If you haven't already, check out the very nice [Expresso store](http://exp-resso.com/store) for Expression Engine.