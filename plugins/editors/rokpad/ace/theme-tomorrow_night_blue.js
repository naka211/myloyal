/*
 * Copyright (c) 2010, Ajax.org B.V.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Ajax.org B.V. nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL AJAX.ORG B.V. BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
ace.define('ace/theme/tomorrow_night_blue', ['require', 'exports', 'module' , 'ace/lib/dom'], function(require, exports, module) {

exports.isDark = true;
exports.cssClass = "ace-tomorrow-night-blue";
exports.cssText = ".ace-tomorrow-night-blue .ace_gutter {\
background: #00204b;\
color: #7388b5\
}\
.ace-tomorrow-night-blue .ace_print-margin {\
width: 1px;\
background: #00204b\
}\
.ace-tomorrow-night-blue {\
background-color: #002451;\
color: #FFFFFF\
}\
.ace-tomorrow-night-blue .ace_constant.ace_other,\
.ace-tomorrow-night-blue .ace_cursor {\
color: #FFFFFF\
}\
.ace-tomorrow-night-blue .ace_marker-layer .ace_selection {\
background: #003F8E\
}\
.ace-tomorrow-night-blue.ace_multiselect .ace_selection.ace_start {\
box-shadow: 0 0 3px 0px #002451;\
border-radius: 2px\
}\
.ace-tomorrow-night-blue .ace_marker-layer .ace_step {\
background: rgb(127, 111, 19)\
}\
.ace-tomorrow-night-blue .ace_marker-layer .ace_bracket {\
margin: -1px 0 0 -1px;\
border: 1px solid #404F7D\
}\
.ace-tomorrow-night-blue .ace_marker-layer .ace_active-line {\
background: #00346E\
}\
.ace-tomorrow-night-blue .ace_gutter-active-line {\
background-color: #022040\
}\
.ace-tomorrow-night-blue .ace_marker-layer .ace_selected-word {\
border: 1px solid #003F8E\
}\
.ace-tomorrow-night-blue .ace_invisible {\
color: #404F7D\
}\
.ace-tomorrow-night-blue .ace_keyword,\
.ace-tomorrow-night-blue .ace_meta,\
.ace-tomorrow-night-blue .ace_storage,\
.ace-tomorrow-night-blue .ace_storage.ace_type,\
.ace-tomorrow-night-blue .ace_support.ace_type {\
color: #EBBBFF\
}\
.ace-tomorrow-night-blue .ace_keyword.ace_operator {\
color: #99FFFF\
}\
.ace-tomorrow-night-blue .ace_constant.ace_character,\
.ace-tomorrow-night-blue .ace_constant.ace_language,\
.ace-tomorrow-night-blue .ace_constant.ace_numeric,\
.ace-tomorrow-night-blue .ace_keyword.ace_other.ace_unit,\
.ace-tomorrow-night-blue .ace_support.ace_constant,\
.ace-tomorrow-night-blue .ace_variable.ace_parameter {\
color: #FFC58F\
}\
.ace-tomorrow-night-blue .ace_invalid {\
color: #FFFFFF;\
background-color: #F99DA5\
}\
.ace-tomorrow-night-blue .ace_invalid.ace_deprecated {\
color: #FFFFFF;\
background-color: #EBBBFF\
}\
.ace-tomorrow-night-blue .ace_fold {\
background-color: #BBDAFF;\
border-color: #FFFFFF\
}\
.ace-tomorrow-night-blue .ace_entity.ace_name.ace_function,\
.ace-tomorrow-night-blue .ace_support.ace_function,\
.ace-tomorrow-night-blue .ace_variable {\
color: #BBDAFF\
}\
.ace-tomorrow-night-blue .ace_support.ace_class,\
.ace-tomorrow-night-blue .ace_support.ace_type {\
color: #FFEEAD\
}\
.ace-tomorrow-night-blue .ace_heading,\
.ace-tomorrow-night-blue .ace_markup.ace_heading,\
.ace-tomorrow-night-blue .ace_string {\
color: #D1F1A9\
}\
.ace-tomorrow-night-blue .ace_entity.ace_name.ace_tag,\
.ace-tomorrow-night-blue .ace_entity.ace_other.ace_attribute-name,\
.ace-tomorrow-night-blue .ace_meta.ace_tag,\
.ace-tomorrow-night-blue .ace_string.ace_regexp,\
.ace-tomorrow-night-blue .ace_variable {\
color: #FF9DA4\
}\
.ace-tomorrow-night-blue .ace_comment {\
color: #7285B7\
}\
.ace-tomorrow-night-blue .ace_indent-guide {\
background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAEklEQVQImWNgYGBgYJDzqfwPAANXAeNsiA+ZAAAAAElFTkSuQmCC) right repeat-y\
}";

var dom = require("../lib/dom");
dom.importCssString(exports.cssText, exports.cssClass);
});