<h3><?php echo __("Edition du match"); ?> <?php echo $match->getTeamA(); ?> vs <?php echo $match->getTeamB(); ?></h3>
<hr/>
<style>
    label.valid {
        width: 24px;
        height: 24px;
        background: url(/img/valid.png) center center no-repeat;
        display: inline-block;
        text-indent: -9999px;
    }
    label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }
</style>

<script>
    $(function() {
        jQuery.validator.addMethod("team_a", function(value, element) { 
            var valid = false;
            if ($(element).attr("name") == "matchs[team_a]") {
                valid = true;
            }
            
            if ($(element).attr("name") == "matchs[team_a_name]") {
                if ($("#matchs_team_a").val() == 0) {
                    if (value != "") {
                        valid = true;
                    }
                } else {
                    valid = true;
                }
            }
            
            if ($(element).attr("name") == "matchs[team_a]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val()) {
                        valid = false;
                    }
                }
            }
            
            return valid; 
        }, "");
        
        jQuery.validator.addMethod("team_b", function(value, element) { 
            var valid = false;
            if ($(element).attr("name") == "matchs[team_b]") {
                valid = true;
            }
            
            if ($(element).attr("name") == "matchs[team_b_name]") {
                if ($("#matchs_team_b").val() == 0) {
                    if (value != "") {
                        valid = true;
                    }
                } else {
                    valid = true;
                }
            }
            
            if ($(element).attr("name") == "matchs[team_b]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val()) {
                        valid = false;
                    }
                }
            }
            
            return valid; 
        }, "");
        
        $('#form-match').validate(
        {
            rules: {
                "matchs[team_a]": {
                    team_a: true                   
                },
                "matchs[team_a_name]": {
                    team_a: true
                },
                "matchs[team_b]": {
                    team_b: true
                },
                "matchs[team_b_name]": {
                    team_b: true
                },
                "matchs[max_round]": {
                    number: true,
                    required: true
                },"matchs[rules]": {
                    minlength: 1,
                    required: true
                }
            },
            highlight: function(label) {
                $(label).closest('.validate-field').addClass('error').removeClass("success");
            },
            success: function(label) {
                label
                .text('OK!').addClass('valid')
                .closest('.validate-field').addClass('success').removeClass("error");
            }
        }).form();
        
        
        
        $("#matchs_team_a").change(
        function() { 
            if ($(this).val() == 0) { 
                $("#team_a").show();
            } else {
                $("#team_a").hide();
            }
        }
    );
            
        $("#matchs_team_b").change(
        function() { 
            if ($(this).val() == 0) { 
                $("#team_b").show();
            } else {
                $("#team_b").hide();
            }
        }
    );
    });

</script>

<table border="0" cellpadding="5" cellspacing="5" width="100%">
    <tr>
        <td width="50%">
            <h5><?php echo __("Edition des informations du matchs"); ?></h5>
            <form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("matchs_edit", $match); ?>">
                <?php echo $form->renderHiddenFields(); ?>
                <div class="well">
                    <div class="control-group">
                        <label class="control-label"><?php echo __("Statut du match"); ?></label>
                        <div class="controls">
                            <?php echo $match->getStatusText(); ?>
                        </div>
                    </div>

                    <?php foreach ($form as $name => $widget): ?>
                        <?php if (in_array($name, array("team_a_flag", "team_b_flag", "team_a_name", "team_b_name"))) continue; ?>
                        <?php if ($widget->isHidden()) continue; ?>
                        <div class="control-group validate-field">
                            <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                            <div class="controls">
                                <?php echo $widget->render(); ?>
                                <?php if ($name == "team_a"): ?>
                                    <span id="team_a">
                                        <span class="validate-field">
                                            <?php echo $form["team_a_name"]->render(array("placeholder" => "Team name")); ?>
                                        </span>
                                        <span class="validate-field">
                                            <?php echo $form["team_a_flag"]->render(); ?>
                                        </span>
                                    </span>
                                <?php endif; ?>
                                <?php if ($name == "team_b"): ?>
                                    <span id="team_b">
                                        <span class="validate-field">
                                            <?php echo $form["team_b_name"]->render(array("placeholder" => "Team name")); ?>
                                        </span>
                                        <span class="validate-field">
                                            <?php echo $form["team_b_flag"]->render(); ?>
                                        </span>
                                    </span>
                                <?php endif; ?>

                                <?php if ($name == "rules"): ?>
                                    <span class="help-inline"><?php echo __("Rentrer le nom de la cfg que vous utilisez sans son extension (si esl5on5.cfg devient esl5on5)"); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="control-group">
                        <label class="control-label"><?php echo __("Server"); ?></label>
                        <div class="controls">
                            <select name="server_id">
                                <option value="0"><?php echo __("Lancer sur un serveur aléatoirement"); ?></option>
                                <?php foreach ($servers as $server): ?>
                                    <?php if (in_array($server->getIp(), $used)) continue; ?>
                                    <?php
                                    if ($server->getId() == $match->getServerId())
                                        echo '<option selected value="' . $server->getId() . '">' . $server->getHostname() . ' - ' . $server->getIp() . '</option>';
                                    else
                                        echo '<option value="' . $server->getId() . '">' . $server->getHostname() . ' - ' . $server->getIp() . '</option>';
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label"><?php echo __("Maps"); ?></label>
                        <div class="controls">
                            <select name="maps">
                                <?php foreach ($maps as $map): ?>
                                    <option <?php if ($map == $match->getMap()->getMapName()) echo "selected"; ?> value="<?php echo $map; ?>"><?php echo $map; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input type="submit" class="btn btn-primary" value="<?php echo __("Sauver le match"); ?>"/>
                        </div>
                    </div>
                </div>
            </form>
        </td>
        <td width="50%" valign="top">
            <h5><?php echo __("Edition des scores du matchs"); ?></h5>
            <?php foreach ($formScores as $form): ?>
                <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_score_edit", $form->getObject()); ?>">
                    <?php echo $form->renderHiddenFields(); ?>
                    <div class="well">
                        <div class="control-group">
                            <label class="control-label"><?php echo __("Type de score"); ?></label>
                            <div class="controls">
                                <?php echo $form->getObject()->getTypeScore(); ?>
                            </div>
                        </div>

                        <?php foreach ($form as $widget): ?>
                            <?php if ($widget->isHidden()) continue; ?>
                            <div class="control-group">
                                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                                <div class="controls">
                                    <?php echo $widget->render(); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" class="btn btn-primary" value="<?php echo __("Sauver les scores"); ?>"/>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>

            <div class="alert alert-danger">
                <?php echo __("<b>Attention</b> - En changeant les scores ici, tous les scores seront recalculés !"); ?>
            </div>
        </td>
    </tr>
</table>
