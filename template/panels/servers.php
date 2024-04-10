<?php

use GameQ\GameQ;

echo '
<div class="panel">
    <div class="panel-head">
        <p><span class="uk-margin-small-right" uk-icon="icon: server"></span> Naše <span style="color: #df3a3a;">servery</span></p>
    </div>
    <div class="panel-body">
        <table class="my-table uk-width-1-1" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <td width="7%" class="uk-text-center">
                        
                    </td>
                    <td width="40%">
                        Názov
                    </td>
                    <td width="20%">
                        IP:Port
                    </td>
                    <td width="13%" class="uk-text-center">
                        Hráči
                    </td>
                    <td width="20%">
                        Mapa
                    </td>
                </tr>
            </thead>
            <tbody>';

                $selectservers = $pdo->select()
                                       ->from('servers');

                $stmtServers = $selectservers->execute();

                while($data = $stmtServers->fetch()) {

                    $server_type    = $data['game'];
                    $server_addr    = $data['addr'];
                    $server_query   = $data['query'];

                    $servers =  array(
                        array(
                            'type'    => $server_type,
                            'host'    => $server_addr,
                            'options' => [
                                'query_port' => $server_query,
                            ],
                        )
                    );

                    $GameQ = new GameQ();
                    $GameQ->addServers($servers);
                    $GameQ->setOption('timeout', 5);

                    $results = $GameQ->process();

                    $server = $results[$server_addr];

                    echo '
                    <tr>
                        <td class="uk-text-center" title="'.$server['gq_name'].'">
                            <img src="'.URL.'/assets/img/'.$server['gq_type'].'.png" style="width: 16px; height: 16px">
                        </td>
                        <td>';

                            if ($server['gq_type'] != "samp") {
                                echo str_replace("Gamesites.cz",'<span class="colorko-txt">Gamesites.cz</span>',$server['gq_hostname']);
                            } else {
                                echo str_replace("Gamesites.cz",'<span class="colorko-txt">Gamesites.cz</span>',$server['servername']);
                            }

                        echo '
                        </td>
                        <td>
                            <a href="'.$server['gq_joinlink'].'">'.$server['gq_address'].':'.$server['gq_port_client'].'</a>
                        </td>
                        <td class="uk-text-center">
                            '.$server['gq_numplayers'].'/'.$server['gq_maxplayers'].'
                        </td>
                        <td>
                            '.$server['map'].'
                        </td>
                    </tr>';

                }

                echo '
            </tbody>
        </table>
    </div>
</div>
';