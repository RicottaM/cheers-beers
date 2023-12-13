{*
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
 *}
 <div class="panel panel-customers" v-show="currentPage === 'customers'">
    <h3 class="panel-heading">
        <span class="panel-heading-icon-container">
            <i class="las la-users  la-2x"></i>
            {l s='Customers' mod='mailchimppro'}
        </span>
        <div class="panel-heading-action">
            <button id="desc-attribute_group-new"
                    v-on:click="saveSettings"
                    v-if="validApiKey"
                    title="{l s='Save settings' mod='mailchimppro'}"
                    class="list-toolbar-btn btn btn-success">
                <i class="las la-save la-2x"></i>
                <span>{l s='Save' mod='mailchimppro'}</span>
            </button>
        </div>
    </h3>
    <div class="panel-body">
        <h2>{l s='Sync customers' mod='mailchimppro'}</h2>
        <div class="form-group">
            <div class="clearfix"></div>
            <div class="btn-group large" role="group">
                <button type="button" class="btn " v-on:click="syncCustomers = true"
                        :class="syncCustomers == true ? 'btn-primary' : 'btn-default'">
                    {l s='Yes' mod='mailchimppro'}
                </button>
                <button type="button" class="btn " v-on:click="syncCustomers = false"
                        :class="syncCustomers == false ? 'btn-primary' : 'btn-default'">
                    {l s='No' mod='mailchimppro'}
                </button>
            </div>
        </div>
        <hr>
        <div :class="syncCustomers == true ? 'sync-settings-container' : 'sync-settings-container no-sync-type'">
            <h2>{l s='Filter customers to sync' mod='mailchimppro'}</h2>
            <div class="form-group">
                <label>{l s='By status' mod='mailchimppro'}</label>
                <div class="clearfix"></div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn "
                            v-on:click="customerSyncFilterEnabled = [0,1]"
                            :class="[arrayEquals(customerSyncFilterEnabled, [0,1]) ? 'btn-primary' : 'btn-default']">
                        {l s='All' mod='mailchimppro'}
                    </button>
                    <button type="button" class="btn "
                            v-on:click="customerSyncFilterEnabled = [1]"
                            :class="[arrayEquals(customerSyncFilterEnabled, [1]) ? 'btn-primary' : 'btn-default']">
                        {l s='Only active' mod='mailchimppro'}
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label>{l s='By newsletter opt in status' mod='mailchimppro'}</label>
                <div class="clearfix"></div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn "
                            v-on:click="customerSyncFilterNewsletter = [0, 1]"
                            :class="[arrayEquals(customerSyncFilterNewsletter, [0,1]) ? 'btn-primary' : 'btn-default']">
                        {l s='All (transactional + opted-in)' mod='mailchimppro'}
                    </button>
                    <button type="button" class="btn "
                            v-on:click="customerSyncFilterNewsletter = [1]"
                            :class="[arrayEquals(customerSyncFilterNewsletter, [1]) ? 'btn-primary' : 'btn-default']">
                        {l s='Only opted-in' mod='mailchimppro'}
                    </button>
                </div>
            </div>
            <hr>
            <h2>{l s='Tag customers' mod='mailchimppro'}</h2>
            <div class="form-group">
                <label>{l s='Add customer groups as tag' mod='mailchimppro'}</label>
                <div class="clearfix"></div>
                {literal}
                    <Multiselect
                        v-model="customerSyncTagDefaultGroup"
                        :can-deselect="false"
                        :can-clear="false"
                        :mode="'single'"
                        :options="[{'label': 'No','value': 'no'},{'label': 'Yes','value': 'all'},{'label': 'Default group only','value': 'default'}]"
                    >
                    </Multiselect>
                {/literal}
            </div>
            <div class="form-group">
                <label>{l s='Add customer gender as tag' mod='mailchimppro'}</label>
                <div class="clearfix"></div>
                {literal}
                    <Multiselect
                        v-model="customerSyncTagGender"
                        :can-deselect="false"
                        :can-clear="false"
                        :mode="'single'"
                        :options="[{'label': 'Yes','value': true},{'label': 'No','value': false}]"
                    >
                    </Multiselect>
                {/literal}
            </div>
        </div>
    </div>
</div>