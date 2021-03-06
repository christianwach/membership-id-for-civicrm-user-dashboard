{*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
*}
{crmRegion name="crm-member-userdashboard-pre"}
{/crmRegion}
<div class="view-content">
  {if $activeMembers}
    <div id="memberships">
      <div class="form-item">
        {strip}
          <table>
            <tr class="columnheader">
              <th>{ts}ID{/ts}</th>
              <th>{ts}Membership{/ts}</th>
              <th>{ts}Member Since{/ts}</th>
              <th>{ts}Start Date{/ts}</th>
              <th>{ts}End Date{/ts}</th>
              <th>{ts}Status{/ts}</th>
              <th></th>
            </tr>
            {foreach from=$activeMembers item=activeMember}
              <tr class="{cycle values="odd-row,even-row"} {$activeMember.class}">
                <td>{$activeMember.id}</td>
                <td>{$activeMember.membership_type}</td>
                <td>{$activeMember.join_date|crmDate}</td>
                <td>{$activeMember.start_date|crmDate}</td>
                <td>{$activeMember.end_date|crmDate}</td>
                <td>{$activeMember.status}</td>
                <td>{if $activeMember.renewPageId}<a href="{crmURL p='civicrm/contribute/transact' q="id=`$activeMember.renewPageId`&mid=`$activeMember.id`&reset=1"}">[ {ts}Renew Now{/ts} ]</a>{/if}</td>
              </tr>
            {/foreach}
          </table>
        {/strip}
      </div>
    </div>
  {/if}
  {if $inActiveMembers}
    <div id="ltype">
      <p></p>
      <div class="label font-red">{ts}Expired / Inactive Memberships{/ts}</div>
      <div class="form-item">
        {strip}
          <table>
            <tr class="columnheader">
              <th>{ts}ID{/ts}</th>
              <th>{ts}Membership{/ts}</th>
              <th>{ts}Start Date{/ts}</th>
              <th>{ts}End Date{/ts}</th>
              <th>{ts}Status{/ts}</th>
              <th></th>
            </tr>
            {foreach from=$inActiveMembers item=inActiveMember}
              <tr class="{cycle values="odd-row,even-row"} {$inActiveMember.class}">
                <td>{$inActiveMember.id}</td>
                <td>{$inActiveMember.membership_type}</td>
                <td>{$inActiveMember.start_date|crmDate}</td>
                <td>{$inActiveMember.end_date|crmDate}</td>
                <td>{$inActiveMember.status}</td>
                <td>{if $inActiveMember.renewPageId}<a href="{crmURL p='civicrm/contribute/transact' q="id=`$inActiveMember.renewPageId`&mid=`$inActiveMember.id`&reset=1"}">[ {ts}Renew Now{/ts} ]</a>{/if}</td>
              </tr>
            {/foreach}
          </table>
        {/strip}

      </div>
    </div>
  {/if}
  {if NOT ($activeMembers or $inActiveMembers)}
     <div class="messages status no-popup">
       {icon icon="fa-info-circle"}{/icon}
       {ts}There are no Memberships on record for you.{/ts}
     </div>
  {/if}
</div>
{crmRegion name="crm-member-userdashboard-post"}
{/crmRegion}
