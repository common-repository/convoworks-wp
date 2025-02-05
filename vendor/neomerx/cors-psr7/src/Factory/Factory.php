<?php

namespace Convoworks\Neomerx\Cors\Factory;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
use Convoworks\Neomerx\Cors\Analyzer;
use Convoworks\Neomerx\Cors\AnalysisResult;
use Convoworks\Neomerx\Cors\Http\ParsedUrl;
use Convoworks\Neomerx\Cors\Contracts\Factory\FactoryInterface;
use Convoworks\Neomerx\Cors\Contracts\AnalysisStrategyInterface;
/**
 * @package Neomerx\Cors
 */
class Factory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createAnalyzer(AnalysisStrategyInterface $strategy)
    {
        return new Analyzer($strategy, $this);
    }
    /**
     * @inheritdoc
     */
    public function createAnalysisResult($requestType, array $responseHeaders = [])
    {
        return new AnalysisResult($requestType, $responseHeaders);
    }
    /**
     * @inheritdoc
     */
    public function createParsedUrl($url)
    {
        return new ParsedUrl($url);
    }
}
