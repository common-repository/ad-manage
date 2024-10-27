<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

function admg_advert_rule_markup( $i = 0, $admg_rule = false ) {

    if ( is_numeric( $admg_rule ) ) {

        $admg_rule = admg_get_rule(array(
            "id" => $admg_rule
        ));
        $admg_form = array(
            "func" => $admg_rule->function,
            "operator" => $admg_rule->operator,
            "result" => $admg_rule->result,
            "parent" => $admg_rule->parent,
        );

    } else if ( $admg_rule ) {

        $admg_form = array(
            "func" => $admg_rule->function,
            "operator" => $admg_rule->operator,
            "result" => $admg_rule->result,
            "parent" => $admg_rule->parent,
        );

    } else {

        $admg_form = array(
            "func" => '',
            "operator" => '',
            "result" => '',
            "parent" => ''
        );

    }

	?>
	<tr class='rule'>
    	<td class="func">
    		<label class="sr-only">Condition</label>
    		<select name="rule[<?php echo $i ?>][function]" class="advert-field rule-func">
        		<option value="<?php echo esc_attr($admg_form['func']) ?>"><?php echo esc_html($admg_form['func']) ?></option>
    		</select>
            <input type="hidden" name="rule[<?php echo $i ?>][parent]" class="advert-field rule-parent" value="<?php echo esc_attr($admg_form['parent']) ?>">
    	</td>
    	<td class="operator">
    		<label class="sr-only">Operator</label>
    		<select name="rule[<?php echo $i ?>][operator]" class="advert-field rule-operator">
        		<option value="<?php echo esc_attr($admg_form['operator']) ?>"><?php echo esc_html($admg_form['operator']) ?></option>
    		</select>
    	</td>
    	<td class="result">
    		<label class="sr-only">Result</label>
    		<input type="text" maxlength="500" name="rule[<?php echo $i ?>][result]" class="advert-field rule-result" value="<?php echo esc_attr($admg_form['result']) ?>">
    	</td>
    	<td class="add">
            <button type="button" role="button" class="button rule-add">and</button>
    	</td>
        <td class="remove">
            <button type="button" role="button" class="button rule-remove">-</button>
        </td>
    </tr>
    <?php
}	
?>