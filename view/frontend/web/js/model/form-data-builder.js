/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    var merge = function(target, source) {
        for (var prop in source)
            if (prop in target)
                merge(target[prop], source[prop]);
            else
                target[prop] = source[prop];
        return target;
    };

    var parseEntry = function (entry) {
        var regexp = /\[[^\[]*\]/g,
            propNames = entry.name.match(regexp),
            data = {};

        if (propNames) {
            // add base name to prop names
            propNames.unshift('[' + entry.name.substr(0, entry.name.indexOf('[')) + ']');

            var lastIndex = propNames.length - 1,
                currentTarget = data;

            for (var i = 0; i < propNames.length; ++i) {
                var propName = propNames[i].replace('[', '')
                    .replace(']', '');

                if (propName === '') {
                    propName = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
                }

                if (currentTarget[propName] === void 0) {
                    currentTarget[propName] = (i === lastIndex) ? entry.value : {};
                }

                currentTarget = currentTarget[propName];
            }

            return data;
        }

        data[entry.name] = entry.value;

        return data;
    };

    var formDataBuilder = function(formElement) {
        var formData = new FormData(formElement),
            output = {};

        formData.forEach(function(value, name) {
            var partData = parseEntry({value: value, name: name});
            output = merge(output, partData);
        });

        return output;
    };

    return formDataBuilder;
});