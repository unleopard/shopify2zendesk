<?php

class accueilController extends Controller
{
    function index()
    {
        $data_status = [];

        array_push($data_status, ['status' => true, 'date' => date('d-m-Y H:i'), 'description' => 'Operation Commence']);

        parent::loadModel('shopify');
        parent::loadModel('log');

        try {
            $commandes = $this->getCommandeFromShopify();

            if (isset($commandes['orders']) && !empty($commandes['orders'])) {
                foreach ($commandes['orders'] as $k => $commande) {

                    $_commande = $this->shopify->commandeExists($commande['id']);

                    if (!$_commande) {

                        $userZendesk = $this->findClientFromZendeskByMail($commande['customer']['email']);

                        if (isset($userZendesk['users']) && !empty($userZendesk['users'])) {
                            $userZendesk = $userZendesk['users'][0];
                        } else {
                            $notes = "";
                            foreach ($commande['line_items'] as $key => $item) {
                                $sep = ($key == 0) ? '' : ' - ';
                                $notes .= $sep . $item['name'] . ' (' . $item['sku'] . ')';
                            }

                            $name = ucfirst(strtolower($commande['customer']['first_name'])) . ' '. strtoupper($commande['customer']['last_name']);
                            $email = $commande['customer']['email'];
                            $phone = str_replace('-', '', $commande['billing_address']['phone']);
                            $details = $commande['billing_address']['address1'] . ', ' . $commande['billing_address']['city'] . ' ' . $commande['billing_address']['zip'] . ' ' . $commande['billing_address']['province'] . ' ' . $commande['billing_address']['country'];
                            $notes = $commande['line_items'][0]['name'] . ' (' . $commande['line_items'][0]['sku'] . ')';
                            $article = $commande['line_items'][0]['name'];
                            $id_client = $commande['customer']['id'];

                            $userZendesk = $this->createZendeskClient($name, $email, $phone, $details, $notes, $article, $id_client);
                            $userZendesk = (!isset($userZendesk['error']) && !recursive_array_search('DuplicateValue', $userZendesk)) ? $userZendesk['user'] : null;
                        }


                        if (!is_null($userZendesk)) {
                            $user = $userZendesk['id'];
                            $cmd_id = $commande['id'];
                            $order_id = $commande['order_number'];

                            $ticket = $this->createTicket($cmd_id, $user, $order_id);

                            if (isset($ticket['ticket']['id']) && preg_match('~\d+~', $ticket['ticket']['id'])) {

                                $id_ticket = $ticket['ticket']['id'];
                                $objShopify = new shopify(null, $commande['id'], $id_ticket, null, date('y-m-d H:i:s'));

                                // nouvelle commande
                                if (!$this->shopify->ajouter($objShopify))
                                    array_push($data_status, ['status' => false, 'date' => date('d-m-Y H:i'), 'methode' => 'this->shopify->ajouter', 'parametres' => [$objShopify], 'description' => "Une erreur est survenue lors de l'insertion de l'objet"]);
                            } else
                                array_push($data_status, ['status' => false, 'date' => date('d-m-Y H:i'), 'methode' => "createTicket", 'parametres' => [$cmd_id, $user, $order_id], 'description' => 'Zendesk n as pas pu cree le ticket']);
                        } else
                            array_push($data_status, ['status' => false, 'date' => date('d-m-Y H:i'), 'methode' => "createZendeskClient", 'parametres' => [$name, $email, $phone, $details, $notes, $article, $id_client], 'description' => 'Zendesk n as pas pu cree le client']);
                    }
                }
            } else
                array_push($data_status, ['status' => false, 'date' => date('d-m-Y H:i'), 'methode' => "getCommandeFromShopify", 'description' => 'pas de commandes']);

        } catch (Exception $e) {
            array_push($data_status, ['status' => false, 'date' => date('d-m-Y H:i'), 'methode' => "Catch", 'description' => $e->getMessage()]);
        }

        array_push($data_status, ['status' => true, 'date' => date('d-m-Y H:i'), 'description' => 'Operation Termine']);


        $this->log->insertLog(new logComandes(null, serialize($data_status), date('Y-m-d H:i:s')));

        die(json_encode(['status' => true]));
    }


    // get all commandes depuis shopify
    private function getCommandeFromShopify($limit = null)
    {
        $commades = $this->curl((is_null($limit) ? SHOPIFY_URL_ORDERS : SHOPIFY_URL_ORDERS . '?limit=' . $limit), SHOPIFY_USER, SHOPIFY_PASS);
        return $commades;
    }

    // find client from zendesk
    private function findClientFromZendeskByMail($mail)
    {
        $header[] = HEADER;

        return $this->curl(ZEN_URL_FIND_USER . '?query=' . $mail, ZEN_USER, ZEN_TOKEN, 'GET', [], $header);
    }

    // create client
    private function createZendeskClient($name, $email, $phone, $details, $notes, $article, $id_client)
    {
        $client = [
            "user" => [
                "name" => $name,
                "email" => $email,
                "verified" => true,
                "phone" => $phone,
                "time_zone" => "Casablanca",
                "iana_time_zone" => "Africa/Casablanca",
                "user_fields" => ["hua_id_client" => $id_client],
                "tags" => ["Huawei"],
                "details" => $details,
                "notes" => $notes
            ]
        ];


        return $this->curl(ZEN_URL_NEW_USER, ZEN_USER, ZEN_TOKEN, 'POST', $client, array(HEADER));
    }

    // creation de ticket
    private function createTicket($id_commande, $user, $order)
    {

        $parametres = [
            "ticket" => [
                "requester_id" => $user,
                "external_id" => $id_commande,
                "subject" => "Commande Huawei Mall  #" . $order,
                "ticket_form_id" => 360000734380,
                "custom_fields" => [
                    ["id" => 360010363699, "value" => "hua"],
                    ["id" => 360010354140, "value" => $id_commande],
                    ["id" => 360010354320, "value" => "initiated"],
                    ["id" => 360010570320, "value" => $order]
                ],
                "comment" => ["public" => false, "body" => "Informations de la commande pour mémoire"]
            ]
        ];

        return $this->curl(ZEN_URL_CREATE_TIKET, ZEN_USER, ZEN_TOKEN, 'POST', $parametres, array(HEADER));
    }

    // CURL connexions
    private function curl($url, $user, $pass, $type = 'GET', $parametres = [], $header = null, $json = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!is_null($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parametres));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return ($json) ? $result : json_decode($result, true);
    }

}