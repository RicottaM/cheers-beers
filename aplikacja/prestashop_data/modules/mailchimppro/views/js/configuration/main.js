/**
 * PrestaChamps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file
 * If you need help please contact leo@prestachamps.com
 *
 * @author    Mailchimp
 * @copyright Mailchimp
 * @license   commercial
 *
 * @var axios
 * @var mailchimp
 */

const {createApp} = Vue

window.app = createApp({
    methods: {
        saveSettings(reason, showResponseMessage) {
            this.isSaving = true;
            axios
                .post(
                    window.configurationUrl + '&action=saveSettings',
                    {
                        action: 'saveSettings',
                        multiInstanceMode: this.multiInstanceMode,
                        cronjobBasedSync: this.cronjobBasedSync,
                        syncProducts: this.syncProducts,
                        syncCustomers: this.syncCustomers,
                        syncCartRules: this.syncCartRules,
                        syncOrders: this.syncOrders,
                        syncCarts: this.syncCarts,
                        syncNewsletterSubscribers: this.syncNewsletterSubscribers,
                        statusForPending: this.statusForPending,
                        statusForRefunded: this.statusForRefunded,
                        statusForCancelled: this.statusForCancelled,
                        statusForShipped: this.statusForShipped,
                        statusForPaid: this.statusForPaid,
                        productDescriptionField: this.productDescriptionField,
                        existingOrderSyncStrategy: this.existingOrderSyncStrategy,
                        productSyncFilterActive: this.productSyncFilterActive,
                        productSyncFilterVisibility: this.productSyncFilterVisibility,
                        customerSyncFilterEnabled: this.customerSyncFilterEnabled,
                        customerSyncFilterNewsletter: this.customerSyncFilterNewsletter,
                        customerSyncTagDefaultGroup: this.customerSyncTagDefaultGroup,
                        customerSyncTagGender: this.customerSyncTagGender,
                        cartRuleSyncFilterStatus: this.cartRuleSyncFilterStatus,
                        cartRuleSyncFilterExpiration: this.cartRuleSyncFilterExpiration,
                        listId: this.listId,
                        storeSynced: this.storeSynced,
                        productImageSize: this.productImageSize,
                        logQueue: this.logQueue,
                        queueStep: this.queueStep,
                        queueAttempt: this.queueAttempt,
                        logCronjob: this.logCronjob
                    }
                )
                .then((response) => {
                        this.isSaving = false;
                        this.getEntityCount();
                        if (showResponseMessage !== false) {
                            this.showSuccess('Update successful!');
                        }
                        if (reason == 'multiInstanceMode') {
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    }
                )
        },
        markReadJsonJobs(reason, showResponseMessage) {
            this.isSaving = true;
            axios
                .post(
                    window.configurationUrl + '&action=markReadJsonJobs',
                    {
                        action: 'markReadJsonJobs',                        
                    }
                )
                .then((response) => {
                        this.isSaving = false;
                        this.getEntityCount();

                        if (showResponseMessage !== false) {
                            this.showSuccess('Update successful!');
                        }

                        setTimeout(function () {
                            location.reload();
                        }, 100);                        
                    }
                )
        },
        syncStore() {
            this.showLoader = true;
            axios
                .post(
                    window.configurationUrl + '&action=syncStores', 
                    {
                        action: 'ajaxProcessSyncStores'
                    }
                )
                .then((response) => {
                        this.showLoader = false;
                        if (response.data.hasError === false) {
                            this.showSuccess(response.data.successMessage);
                            this.storeSynced = true;
                            $("#content > .bootstrap .alert-info").addClass('hidden');
                        }
                        else {
                            this.showError(response.data.errorMessage);
                            this.storeSynced = false;
                        }
                    }
                )
        },
        getEntityCount() {
            axios
                .post(
                    window.configurationUrl + '&action=getEntityCount',
                    {
                        action: 'getEntityCount',
                    }
                )
                .then((response) => {
                        this.numberOfCartRulesToSync = response.data.cartRules;
                        this.numberOfCustomersToSync = response.data.customers;
                        this.numberOfProductsToSync = response.data.products;
                        this.numberOfOrdersToSync = response.data.orders;
                        this.numberOfNewsletterSubscribersToSync = response.data.newsletterSubscribers;
                    }
                )
        },
        updateStaticContent() {
            axios
                .post(
                    window.configurationUrl + '&action=updateStaticContent',
                    {
                        action: 'ajaxProcessUpdateStaticContent',
                    }
                )
                .then((response) => {
                        this.cronjobLogContent = response.data.cronjobLogContent;
                        this.lastSyncedProductId = response.data.lastSyncedProductId ?? '–';
                        this.lastSyncedCustomerId = response.data.lastSyncedCustomerId ?? '–';
                        this.lastSyncedCartRuleId = response.data.lastSyncedCartRuleId ?? '–';
                        this.lastSyncedOrderId = response.data.lastSyncedOrderId ?? '–';
                        this.lastSyncedCartId = response.data.lastSyncedCartId ?? '–';
                        this.lastSyncedNewsletterSubscriberId = response.data.lastSyncedNewsletterSubscriberId ?? '–';
                        this.lastCronjob = response.data.lastCronjob ?? '–';
                        this.lastCronjobExecutionTime = response.data.lastCronjobExecutionTime ?? '–';
                        this.totalJobs = response.data.totalJobs ?? '–';
                    }
                )
        },
        disconnect() {
            if (confirm("Do you really want to log out?")) {
                axios
                    .post(
                        window.configurationUrl + '&action=disconnect',
                        {
                            action: 'disconnect',
                        }
                    )
                    .then((response) => {
                            this.accountInfo = response.data.accountInfo;
                            this.validApiKey = response.data.validApiKey;
                            
                            $('#content > .bootstrap .alert-info').addClass('hidden');
                        }
                    )
            }
        },
        oauthStart() {
            let width = 500;
            let height = 900;
            let left = (screen.width / 2) - (width / 2);
            let top = (screen.height / 2) - (height / 2);

            window.open(
                window.middlewareUrl,
                "McAuthMiddleware",
                'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ",resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes"
            );
        },
        setCurrentPage(currentPage) {
            this.currentPage = currentPage;
            const url = new URL(window.location);
            url.hash = '#' + currentPage;
            window.history.pushState({}, '', url);
            
            this.updateStaticContent();
        },
        arrayEquals(a, b) {
            if (a.length !== b.length) return false;
            const uniqueValues = new Set([...a, ...b]);
            for (const v of uniqueValues) {
                const aCount = a.filter(e => e === v).length;
                const bCount = b.filter(e => e === v).length;
                if (aCount !== bCount) return false;
            }
            return true;
        },
        pushSetupJobsToQueue() {
			this.isSaving = true;
            let requests = [];
            // stores
            requests.push(axios.post(window.configurationUrl + '&action=syncStores', {action: 'ajaxProcessSyncStores'}))

            if (this.syncProducts) {
                requests.push(axios.post(window.configurationUrl + '&action=addProductsToQueue', {action: 'ajaxProcessAddProductsToQueue'}));
                
                // filling the specific price table with initial values
                requests.push(axios.post(window.configurationUrl + '&action=initializeSpecificPrices', {action: 'ajaxProcessSpecificPrices'}));
            }
            if (this.syncCustomers) {
                requests.push(axios.post(window.configurationUrl + '&action=addCustomersToQueue', {action: 'ajaxProcessAddCustomersToQueue'}));
            }
            if (this.syncOrders) {
                requests.push(axios.post(window.configurationUrl + '&action=addOrdersToQueue', {action: 'ajaxProcessAddOrdersToQueue'}));
            }
            if (this.syncCartRules) {
                requests.push(axios.post(window.configurationUrl + '&action=addCartRulesToQueue', {action: 'ajaxProcessAddCartRulesToQueue'}));
            }
            if (this.syncNewsletterSubscribers) {
                requests.push(axios.post(window.configurationUrl + '&action=addNewsletterSubscribersToQueue', {action: 'ajaxProcessAddNewsletterSubscribersToQueue'}));
            }

            axios
                .all(requests)
                .then(
                    axios.spread((...responses) => {
                        console.log(responses);
						this.jobsAddedToQueue = true;
						this.isSaving = false;
                        //window.location = window.queueUrl;
                    })
                )
                .catch(errors => {
                    // react on errors.
                    console.error(errors);
					this.isSaving = false;
                });
        },
        deleteMailchimpEcommerceData() {
            if (confirm("Do you really want to delete?")) {
                this.showLoader = true;
                axios
                    .post(
                        window.configurationUrl + '&action=deleteEcommerceData',
                        {
                            action: 'ajaxProcessDeleteEcommerceData',
                        }
                    )
                    .then((response) => {
                            this.showLoader = false;
                            if (response.data.hasError === false) {
                                this.showSuccess(response.data.successMessage);
                            }
                            else {
                                this.showError(response.data.errorMessage);
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    )
            }
        },
        executeCronjob(e) {
            e.preventDefault();
            this.showLoader = true;
            axios
                .post(
                    window.configurationUrl + '&action=executeCronjob&secure=' + window.cronjobSecureToken,
                    {
                        action: 'ajaxProcessExecuteCronjob',
                    }
                )
                .then((response) => {
                        this.showLoader = false;
                        if (response.data.errors) {
                            this.showError(response.data.errors);
                        }
                        else {
                            if (response.data.result) {
                                this.showSuccess(response.data.result);
                            }
                        }

                        this.updateStaticContent();
                    }
                )
        },
        clearCronjobLog() {
            if (confirm("Do you really want to clear the cronjob log?")) {
                this.showLoader = true;
                axios
                    .post(
                        window.configurationUrl + '&action=clearCronjobLog',
                        {
                            action: 'ajaxProcessClearCronjobLog',
                        }
                    )
                    .then((response) => {
                            this.showLoader = false;
                            if (response.data.hasError === false) {
                                this.showSuccess(response.data.successMessage);
                            }
                            else {
                                this.showError(response.data.errorMessage);
                            }

                            this.updateStaticContent();
                        }
                    )
            }
        },
        showError(message) {
            Toastify({
                text: message,
                duration: 2000,
                close: true,
                gravity: "top",
                position: 'center',
                style: {
                    background: "#ff0000",
                },
                stopOnFocus: false,
            }).showToast();
        },
		showSuccess(message) {
            Toastify({
                text: message,
                duration: 2000,
                close: true,
                gravity: "top",
                position: 'center',
                style: {
                    background: "#1a8f35",
                },
                stopOnFocus: false,
            }).showToast();
        }
    },
    watch: {
        listId: function () {
            if (this.listId) {
                this.storeSynced = false;
            }
            this.saveSettings();
        },
        multiInstanceMode: function () {
            if (this.storeInstanceMode != this.multiInstanceMode) {
                this.storeSynced = false;
                this.listId = false;
                this.saveSettings("multiInstanceMode", false);
            }
            else {
                this.saveSettings("multiInstanceMode", true);
            }
        },
        cronjobBasedSync: function () {
            this.saveSettings();
        },
        syncProducts: function () {
            this.saveSettings();
        },
        syncCustomers: function () {
            this.saveSettings();
        },
        syncCartRules: function () {
            this.saveSettings();
        },
        syncOrders: function () {
            this.saveSettings();
        },
        syncCarts: function () {
            this.saveSettings();
        },
        syncNewsletterSubscribers: function () {
            this.saveSettings();
        },
        statusForPending: function () {
            this.saveSettings();
        },
        statusForRefunded: function () {
            this.saveSettings();
        },
        statusForCancelled: function () {
            this.saveSettings();
        },
        statusForShipped: function () {
            this.saveSettings();
        },
        statusForPaid: function () {
            this.saveSettings();
        },
        productDescriptionField: function () {
            this.saveSettings();
        },
        existingOrderSyncStrategy: function () {
            this.saveSettings();
        },
        productSyncFilterActive: function () {
            this.saveSettings();
        },
        productSyncFilterVisibility: function () {
            this.saveSettings();
        },
        customerSyncFilterEnabled: function () {
            this.saveSettings();
        },
        customerSyncFilterNewsletter: function () {
            this.saveSettings();
        },
        customerSyncTagDefaultGroup: function () {
            this.saveSettings();
        },
        customerSyncTagGender: function () {
            this.saveSettings();
        },
        cartRuleSyncFilterStatus: function () {
            this.saveSettings();
        },
        cartRuleSyncFilterExpiration: function () {
            this.saveSettings();
        },
        productImageSize: function () {
            this.saveSettings();
        },
		logQueue: function () {
            this.saveSettings();
        },
		queueStepRaw: function () {
			if (!isNaN(parseInt(this.queueStepRaw)) && parseInt(this.queueStepRaw) > 0) {
				this.queueStepRaw = parseInt(this.queueStepRaw);
				if (this.queueStep != this.queueStepRaw) {
					this.queueStep = this.queueStepRaw;
					this.saveSettings();
				}
			}
			else {
				this.showError('Invalid queue step!');
			}
        },
		queueAttemptRaw: function () {
			if (!isNaN(parseInt(this.queueAttemptRaw)) && parseInt(this.queueAttemptRaw) > 0) {
				this.queueAttemptRaw = parseInt(this.queueAttemptRaw);
				if (this.queueAttempt != this.queueAttemptRaw) {
					this.queueAttempt = this.queueAttemptRaw;
					this.saveSettings();
				}
			}
			else {
				this.showError('Invalid queue max-trying time!');
			}
        },
        logCronjob: function () {
            this.saveSettings();
        }
    },
    mounted() {
        this.timer = setInterval(() => {
            const url = new URL(window.location);
            if (url.hash.length > 0 && url.hash.replace("#", '') !== this.currentPage) {
                this.currentPage = url.hash.replace("#", '');
            }
        }, 100)
        window.addEventListener(
            "message",
            (event) => {
                if (event.origin !== window.middlewareUrl) {
                    return false;
                }
                if (event.data.hasOwnProperty('token') && event.data.hasOwnProperty('user')) {
                    const token = event.data.token + "-" + event.data.user.dc;
                    axios
                        .post(
                            window.configurationUrl + '&action=connect',
                            {
                                action: 'connect',
                                token: token
                            }
                        )
                        .then((response) => {
                                /* this.accountInfo = response.data.accountInfo;
                                this.validApiKey = response.data.validApiKey; */
                                
                                //setTimeout(function () {
                                    location.reload();
                                //}, 500);
                            }
                        )
                }
            },
            true
        );
    },
    data() {
        return {
            isSaving: false,
            showLoader: false,
			jobsAddedToQueue: false,
            currentPage: 'accountInfo',
            listId: window.mailchimp.listId,
            lists: window.mailchimp.lists,
            storeAlreadySynced: window.storeAlreadySynced ?? false,
            storeSynced: window.mailchimp.storeSynced,
            orderStates: window.mailchimp.orderStates,
            storeInstanceMode: window.mailchimp.multiInstanceMode,
            multiInstanceMode: window.mailchimp.multiInstanceMode,
            cronjobBasedSync: window.mailchimp.cronjobBasedSync,
            syncProducts: window.mailchimp.syncProducts,
            syncCustomers: window.mailchimp.syncCustomers,
            syncCartRules: window.mailchimp.syncCartRules,
            syncOrders: window.mailchimp.syncOrders,
            syncCarts: window.mailchimp.syncCarts,
            syncNewsletterSubscribers: window.mailchimp.syncNewsletterSubscribers,
            statusForPending: (window.mailchimp.statusForPending),
            statusForRefunded: (window.mailchimp.statusForRefunded),
            statusForCancelled: (window.mailchimp.statusForCancelled),
            statusForShipped: (window.mailchimp.statusForShipped),
            statusForPaid: (window.mailchimp.statusForPaid),
            productDescriptionField: window.mailchimp.productDescriptionField,
            existingOrderSyncStrategy: window.mailchimp.existingOrderSyncStrategy,
            productSyncFilterActive: (window.mailchimp.productSyncFilterActive),
            productSyncFilterVisibility: (window.mailchimp.productSyncFilterVisibility),
            customerSyncFilterEnabled: (window.mailchimp.customerSyncFilterEnabled),
            customerSyncFilterNewsletter: (window.mailchimp.customerSyncFilterNewsletter),
            customerSyncTagDefaultGroup: window.mailchimp.customerSyncTagDefaultGroup,
            customerSyncTagGender: window.mailchimp.customerSyncTagGender,
            cartRuleSyncFilterStatus: (window.mailchimp.cartRuleSyncFilterStatus),
            cartRuleSyncFilterExpiration: (window.mailchimp.cartRuleSyncFilterExpiration),
            productImageSize: window.mailchimp.productImageSize,
            token: window.mailchimp.token,
            validApiKey: window.mailchimp.validApiKey,
            accountInfo: window.mailchimp.accountInfo,
            numberOfCartRulesToSync: window.mailchimp.numberOfCartRulesToSync,
            numberOfCustomersToSync: window.mailchimp.numberOfCustomersToSync,
            numberOfProductsToSync: window.mailchimp.numberOfProductsToSync,
            numberOfOrdersToSync: window.mailchimp.numberOfOrdersToSync,
            numberOfNewsletterSubscribersToSync: window.mailchimp.numberOfNewsletterSubscribersToSync,
            logQueue: window.mailchimp.logQueue,
            queueStep: window.mailchimp.queueStep,
            queueStepRaw: window.mailchimp.queueStep,
            queueAttempt: window.mailchimp.queueAttempt,
            queueAttemptRaw: window.mailchimp.queueAttempt,
			logCronjob: window.mailchimp.logCronjob,
			cronjobLogContent: window.mailchimp.cronjobLogContent,
			lastSyncedProductId: window.mailchimp.lastSyncedProductId ?? '–',
			lastSyncedCustomerId: window.mailchimp.lastSyncedCustomerId ?? '–',
            lastSyncedCartRuleId: window.mailchimp.lastSyncedCartRuleId ?? '–',
			lastSyncedOrderId: window.mailchimp.lastSyncedOrderId ?? '–',
            lastSyncedCartId: window.mailchimp.lastSyncedCartId ?? '–',
			lastSyncedNewsletterSubscriberId: window.mailchimp.lastSyncedNewsletterSubscriberId ?? '–',
			lastCronjob: window.mailchimp.lastCronjob ?? '–',
			lastCronjobExecutionTime: window.mailchimp.lastCronjobExecutionTime ?? '–',
			totalJobs: window.mailchimp.totalJobs ?? '–',
        }
    }
});
window.app.component('multiselect', window.VueformMultiselect)
window.app.mount('#app')