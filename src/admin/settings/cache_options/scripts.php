<?php

/*
  Copyright (c) 2016 Translation Exchange, Inc

   _______                  _       _   _             ______          _
  |__   __|                | |     | | (_)           |  ____|        | |
     | |_ __ __ _ _ __  ___| | __ _| |_ _  ___  _ __ | |__  __  _____| |__   __ _ _ __   __ _  ___
     | | '__/ _` | '_ \/ __| |/ _` | __| |/ _ \| '_ \|  __| \ \/ / __| '_ \ / _` | '_ \ / _` |/ _ \
     | | | | (_| | | | \__ \ | (_| | |_| | (_) | | | | |____ >  < (__| | | | (_| | | | | (_| |  __/
     |_|_|  \__,_|_| |_|___/_|\__,_|\__|_|\___/|_| |_|______/_/\_\___|_| |_|\__,_|_| |_|\__, |\___|
                                                                                         __/ |
                                                                                        |___/
    GNU General Public License, version 2

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

    http://www.gnu.org/licenses/gpl-2.0.html
*/

?>

<script>
    function toggleSnapshot(version) {
//        alert(jQuery('snapshot-' + version + '-arrow'));
        var arrow = jQuery('#snapshot-' + version + '-arrow');
        if (arrow.attr('class') == 'arrow-right') {
            arrow.removeClass('arrow-right');
            arrow.addClass('arrow-down');
            jQuery('#snapshot-' + version + '-details').show();
        } else {
            arrow.removeClass('arrow-down');
            arrow.addClass('arrow-right');
            jQuery('#snapshot-' + version + '-details').hide();
        }
    }

    function editDynamicCache() {
        document.getElementById("tml_cache_adapter").disabled = false;
        document.getElementById("tml_cache_host").disabled = false;
        document.getElementById("tml_cache_port").disabled = false;
        document.getElementById("tml_cache_namespace").disabled = false;
        document.getElementById("tml_cache_version_check_interval").disabled = false;

        document.getElementById("tml_edit_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_reset_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_save_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_cancel_dynamic_cache_button").style.display = 'inline';
    }

    function saveDynamicCache() {
        cancelDynamicCacheEdit();
        var select = document.getElementById("tml_cache_adapter");
        jQuery("#cache_action").val("use_cache");
        jQuery("#cache_type").val('dynamic');
        jQuery("#cache_adapter").val(select.options[select.selectedIndex].value);
        jQuery("#cache_host").val(document.getElementById("tml_cache_host").value);
        jQuery("#cache_port").val(document.getElementById("tml_cache_port").value);
        jQuery("#cache_namespace").val(document.getElementById("tml_cache_namespace").value);
        jQuery("#cache_version_check_interval").val(document.getElementById("tml_cache_version_check_interval").value);
        document.getElementById("cache_form").submit();
    }

    function cancelDynamicCacheEdit() {
        document.getElementById("tml_cache_adapter").disabled = true;
        document.getElementById("tml_cache_host").disabled = true;
        document.getElementById("tml_cache_port").disabled = true;
        document.getElementById("tml_cache_namespace").disabled = true;
        document.getElementById("tml_cache_version_check_interval").disabled = true;

        document.getElementById("tml_edit_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_reset_dynamic_cache_button").style.display = 'inline';
        document.getElementById("tml_save_dynamic_cache_button").style.display = 'none';
        document.getElementById("tml_cancel_dynamic_cache_button").style.display = 'none';
    }

    function resetBrowserCache() {
        if (!confirm("<?php echo __("Are you sure you want to reset browser cache?") ?>"))
            return false;

        var cache = window.localStorage;
        for (var key in cache) {
            if (key.match(/^tml_/)) cache.removeItem(key);
        }
        window.location.reload();
        return false;
    }

    function downloadSnapshot() {
        if (!confirm("<?php echo __("Are you sure you want to download the latest snapshot from Translation Exchange?") ?>"))
            return false;
        document.getElementById("cache_form").submit();
        return true;
    }

    function deleteCache(version) {
        if (!confirm("<?php echo __("Are you sure you want to remove this cache version?") ?>"))
            return false;

        jQuery("#cache_action").val("delete_cache");
        jQuery("#cache_version").val(version);
        document.getElementById("cache_form").submit();
    }

    function useCache(type, version) {
        jQuery("#cache_action").val("use_cache");
        jQuery("#cache_type").val(type);
        jQuery("#cache_version").val(version);
        document.getElementById("cache_form").submit();
    }

    function syncDynamicCache() {
        if (!confirm("<?php echo __("Are you sure you want to update your cache version to the current release version from Translation Exchange?") ?>"))
            return false;

        jQuery("#cache_action").val("sync_cache");
        document.getElementById("cache_form").submit();
    }

    function showScriptOptions() {
        document.getElementById("tml_script_host").style.display = '';
        document.getElementById("tml_script_options").style.display = '';
        document.getElementById("tml_script_options_button").style.display = 'none';
    }

    function showAgentOptions() {
        document.getElementById("tml_host").style.display = '';
        document.getElementById("tml_agent_host").style.display = '';
        document.getElementById("tml_agent_options").style.display = '';
        document.getElementById("tml_agent_options_button").style.display = 'none';
    }

</script>