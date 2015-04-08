<table class="table table-bordered sys_table">
    <th>Date</th>
    <th>Account</th>
    <th>Type</th>

    <th class="text-right">Amount</th>

    <th>Description</th>
    <th class="text-right">Dr.</th>
    <th class="text-right">Cr.</th>
    <th class="text-right">Balance</th>
    <th>Manage</th>
    {foreach $tr as $ds}
        <tr class="{if $ds['cr'] eq '0.00'}warning {else}info{/if}">
            <td>{date( $_c['df'], strtotime($ds['date']))}</td>
            <td>{$ds['account']}</td>
            <td>{$ds['type']}</td>

            <td class="text-right">{number_format($ds['amount'],2,$_c['dec_point'],$_c['thousands_sep'])}</td>
            <td>{$ds['description']}</td>
            <td class="text-right">{$_c['currency_code']} {number_format($ds['dr'],2,$_c['dec_point'],$_c['thousands_sep'])}</td>
            <td class="text-right">{$_c['currency_code']} {number_format($ds['cr'],2,$_c['dec_point'],$_c['thousands_sep'])}</td>
            <td class="text-right"><span {if $ds['bal'] < 0}class="text-red"{/if}>{$_c['currency_code']} {number_format($ds['bal'],2,$_c['dec_point'],$_c['thousands_sep'])}</span></td>
            <td><a href="{$_url}transactions/manage/{$ds['id']}/">Manage</a></td>
        </tr>
    {/foreach}



</table>