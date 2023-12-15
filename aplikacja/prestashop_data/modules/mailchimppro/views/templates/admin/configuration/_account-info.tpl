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
<div class="panel panel-account-info" v-show="currentPage === 'accountInfo'">
    <h3 class="panel-heading">
        <span class="panel-heading-icon-container">
            <i class="las la-user-cog la-2x"></i>
            {l s='Account info' mod='mailchimppro'}
        </span>
        <div class="panel-heading-action">
            <button id="desc-attribute_group-new"
                    v-on:click="disconnect"
                    v-if="validApiKey"
                    title="{l s='Log out from your Mailchimp account' mod='mailchimppro'}"
                    class="list-toolbar-btn btn btn-logout {* btn-link *}">					
                <i class="las la-sign-out-alt la-2x"></i>
				<span>{l s='Log out' mod='mailchimppro'}</span>
            </button>
        </div>
    </h3>
    {literal}
    <div class="panel-body" v-if="validApiKey">
        <img :src="accountInfo.avatar_url" alt="avatar" style="max-height: 64px" class="d-inline-block">
        <h2 class="d-inline-block" style="margin-left: 15px">
            {{accountInfo.first_name}} {{accountInfo.last_name}} - {{accountInfo.account_name}}
        </h2>
        <table class="table table-responsive" style="margin-top: 15px">
            <tr>
                <td>
                    {/literal}{l s='Account ID' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.account_id}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Login ID' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.login_id}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Account name' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.account_name}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Email' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.email}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Name' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.first_name}} {{accountInfo.last_name}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Username' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.username}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Role' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.role}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Member since' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.member_since}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Pricing plan type' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.pricing_plan_type}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Account timezone' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.account_timezone}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Pro enabled' mod='mailchimppro'}{literal}
                </td>
                <td>
                    <span v-if="accountInfo.pro_enabled">✔️</span>
                    <span v-if="!accountInfo.pro_enabled">❌️</span>
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Contact' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.contact.addr1}} {{accountInfo.contact.addr2}},
                    {{accountInfo.contact.city}},
                    {{accountInfo.contact.company}},
                    {{accountInfo.contact.country}},
                    {{accountInfo.contact.state}},
                    {{accountInfo.contact.zip}}
                </td>
            </tr>
            <tr>
                <td>
                    {/literal}{l s='Total subscribers' mod='mailchimppro'}{literal}
                </td>
                <td>
                    {{accountInfo.total_subscribers}}
                </td>
            </tr>
        </table>
    </div>
    {/literal}

    <div class="panel-body" v-if="!validApiKey">
        <p class="text-center">
            {l s='In order to get started, you need to log in to you Mailchimp account. In order to do that please click the button below.' mod='mailchimppro'}
        </p>
        <br>
        <div class="row">
            <div class="col-xs-12 text-center">
                <button v-on:click="oauthStart" class="btn btn-default btn-login">
                    {l s='Log-in to Mailchimp' mod='mailchimppro'}
                </button>
            </div>
        </div>
    </div>
</div>