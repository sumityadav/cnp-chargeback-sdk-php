<?php

/*
 * Copyright (c) 2011 Vantiv eCommerce Inc.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace cnp\sdk;
require_once realpath(dirname(__FILE__)) . '/Chargeback.php';

class ChargebackUpdate
{
    private $useSimpleXml = false;
    private $config;
    private $comm;

    public function __construct($treeResponse = false, $overrides = array())
    {
        $this->useSimpleXml = $treeResponse;
        $this->config = Utils::getConfig($overrides);
        $this->comm = new Communication();
    }

    public function assignCaseToUser($case_id, $user_id, $note)
    {
        $hash = array('activityType' => 'ASSIGN_TO_USER',
            'assignedTo' => $user_id,
            'note' => $note);

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    public function addNoteToCase($case_id, $note)
    {
        $hash = array('activityType' => 'ADD_NOTE',
            'note' => $note);

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    public function assumeLiability($case_id, $note)
    {
        $hash = array('activityType' => 'MERCHANT_ACCEPTS_LIABILITY',
            'note' => $note);

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    public function representCase($case_id, $note, $representment_amount = NULL)
    {
        $hash = array('activityType' => 'MERCHANT_REPRESENT',
            'note' => $note);

        if ($representment_amount != NULL) {
            $hash['representedAmount'] = $representment_amount;
        }

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    public function respondToRetrievalRequest($case_id, $note)
    {
        $hash = array('activityType' => 'MERCHANT_RESPOND',
            'note' => $note);

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    public function requestArbitration($case_id, $note)
    {
        $hash = array('activityType' => 'MERCHANT_REQUESTS_ARBITRATION',
            'note' => $note);

        $request_body = Utils::generateChargebackUpdateRequest($hash);
        return $this->getUpdateResponse($case_id, $request_body);
    }

    private function getUpdateResponse($case_id, $request_body)
    {
        echo $request_body;
        $request_url = $this->config['url'] . "/" . $case_id;
        return $this->comm->httpPutRequest($request_url, $request_body, $this->config, $this->useSimpleXml);
    }
}
