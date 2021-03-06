<?php
/**
 * Copyright (c) 2017, Nosto Solutions Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its contributors
 * may be used to endorse or promote products derived from this software without
 * specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nosto Solutions Ltd <contact@nosto.com>
 * @copyright 2017 Nosto Solutions Ltd
 * @license http://opensource.org/licenses/BSD-3-Clause BSD 3-Clause
 *
 */

namespace Nosto\Tagging\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Nosto\Nosto;
use Nosto\Tagging\Helper\Account as NostoHelperAccount;
use Nosto\Tagging\Helper\Data as NostoHelperData;
use Nosto\Tagging\Helper\Scope as NostoHelperScope;

/**
 * Embed script block that includes the Nosto script in the page <head>.
 * This block should be included on all pages.
 */
class Embed extends Template
{
    use TaggingTrait {
        TaggingTrait::__construct as taggingConstruct;
    }

    private $nostoHelperData;
    /**
     * The default Nosto server address to use if none is configured.
     */
    const DEFAULT_SERVER_ADDRESS = 'connect.nosto.com';

    /**
     * Constructor.
     *
     * @param Context $context the context.
     * @param NostoHelperAccount $nostoHelperAccount the account helper.
     * @param NostoHelperData $nostoHelperData the data helper.
     * @param NostoHelperScope $nostoHelperScope
     * @param array $data optional data.
     */
    public function __construct(
        Context $context,
        NostoHelperAccount $nostoHelperAccount,
        NostoHelperData $nostoHelperData,
        NostoHelperScope $nostoHelperScope,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->taggingConstruct($nostoHelperAccount, $nostoHelperScope);
        $this->nostoHelperData = $nostoHelperData;
    }

    /**
     * Returns the account name for the current store.
     *
     * @return string the account name or empty string if account is not found.
     */
    public function getAccountName()
    {
        $store = $this->nostoHelperScope->getStore(true);
        $account = $this->nostoHelperAccount->findAccount($store);
        return $account !== null ? $account->getName() : '';
    }

    /**
     * Returns the Nosto server address.
     * This is taken from the local environment if it is set, or else it
     * defaults to "connect.nosto.com".
     *
     * @return string the url.
     */
    public function getServerAddress()
    {
        return Nosto::getEnvVariable('NOSTO_SERVER_URL', self::DEFAULT_SERVER_ADDRESS);
    }
}
