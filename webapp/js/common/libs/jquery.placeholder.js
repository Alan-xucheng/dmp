;jQuery.extend({
	valHooks: {
            // 对option的操作
            option: {
 
                get: function (elem) {
                    // attributes.value is undefined in Blackberry 4.7 but
                    // uses .value. See #6932
                    // 返回节点属性的value对象
                    var val = elem.attributes.value;
                    // specified:检测是否在HTML中设置了属性值，设置了返回true，否者返回false
                    // 因为select下的option有value和text两种值，如果存在value属性，将返回value值，否者返回option的text文本
                    return !val || val.specified ? elem.value : elem.text;
                }
            },
            // 对select的操作
            select: {
                get: function (elem) {
                    var value, option,
                        options = elem.options,
                        index = elem.selectedIndex,//选中的下标
                        one = elem.type === "select-one" || index < 0,//元素的类型，单选还是多选,多选：select-multiple
                        values = one ? null : [],
                        max = one ? index + 1 : options.length,
                        i = index < 0 ? //未选中返回空值
                        max :
                            one ? index : 0;
 
                    // Loop through all the selected options
                    //遍历所有选中的项
                    for (; i < max; i++) {
                        option = options[i];
 
                        // oldIE doesn't update selected after form reset (#2551)
                        // 检测option是否被禁止，禁止则返回true。，执行判断语句.禁止返回true，接着，是则返回true
                        if ((option.selected || i === index) &&
                            // Don't return options that are disabled or in a disabled optgroup
                                (jQuery.support.optDisabled ? !option.disabled : option.getAttribute("disabled") === null) &&
                            // 检测option的父级是否被禁止，未被禁止返回false
                            (!option.parentNode.disabled ||
                            // 检测父级是否为optgroup
                            !jQuery.nodeName(option.parentNode, "optgroup"))) {
 
                            // Get the specific value for the option
                            // 获取选中的值
                            value = jQuery(option).val();
 
                            // We don't need an array for one selects
                            // 单选择框
                            if (one) {
                                return value;
                            }
 
                            // Multi-Selects return an array
                            // 多选择框
                            values.push(value);
                        }
                    }
                    // 1.8以上版本将此处删除，BUG 修复
                    //if (one && !values.length && options.length) {
                    //return jQuery(options[index]).val();
                    //}
                    return values;
                },
 
                set: function (elem, value) {
                    // 将value转化为数组values
                    var values = jQuery.makeArray(value);
                    // 选中设置的val值
                    jQuery(elem).find("option").each(function () {
                        this.selected = jQuery.inArray(jQuery(this).val(), values) >= 0;
                    });
                    //检测无此值,返回初始化状态
                    if (!values.length) {
                        elem.selectedIndex = -1;
                    }
                    return values;
                }
            }
        },
        //DOM Element属性的映射表
        propHooks: {
            //使用"Tab"键的遍历顺序
            tabIndex: {
                get: function (elem) {
                    // elem.tabIndex doesn't always return the correct value when it hasn't been explicitly set
                    // http://fluidproject.org/blog/2008/01/09/getting-setting-and-removing-tabindex-values-with-javascript/
                    var attributeNode = elem.getAttributeNode("tabindex");
 
                    return attributeNode && attributeNode.specified ?
                        parseInt(attributeNode.value, 10) :
                        rfocusable.test(elem.nodeName) || rclickable.test(elem.nodeName) && elem.href ?
                            0 :
                            undefined;
                }
            }
        }
});

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(window, document, $) {

	var isInputSupported = 'placeholder' in document.createElement('input');
	var isTextareaSupported = 'placeholder' in document.createElement('textarea');
	var prototype = $.fn;
	var valHooks = $.valHooks;
	var propHooks = $.propHooks;
	var hooks;
	var placeholder;

	if (isInputSupported && isTextareaSupported) {

		placeholder = prototype.placeholder = function() {
			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function() {
			var $this = this;
			$this
				.filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
				.not('.placeholder')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			'get': function(element) {
				var $element = $(element);

				var $passwordInput = $element.data('placeholder-password');
				if ($passwordInput) {
					return $passwordInput[0].value;
				}

				return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
			},
			'set': function(element, value) {
				var $element = $(element);

				var $passwordInput = $element.data('placeholder-password');
				if ($passwordInput) {
					return $passwordInput[0].value = value;
				}

				if (!$element.data('placeholder-enabled')) {
					return element.value = value;
				}
				if (value == '') {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if (element != safeActiveElement()) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call(element);
					}
				} else if ($element.hasClass('placeholder')) {
					clearPlaceholder.call(element, true, value) || (element.value = value);
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		if (!isInputSupported) {
			valHooks.input = hooks;
			propHooks.value = hooks;
		}
		if (!isTextareaSupported) {
			valHooks.textarea = hooks;
			propHooks.value = hooks;
		}

		$(function() {
			// Look for forms
			$(document).delegate('form', 'submit.placeholder', function() {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $('.placeholder', this).each(clearPlaceholder);
				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		// Clear placeholder values upon page reload
		$(window).bind('beforeunload.placeholder', function() {
			$('.placeholder').each(function() {
				this.value = '';
			});
		});

	}

	function args(elem) {
		// Return an object of element attributes
		var newAttrs = {};
		var rinlinejQuery = /^jQuery\d+$/;
		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});
		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this;
		var $input = $(input);
		if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
			if ($input.data('placeholder-password')) {
				$input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if (event === true) {
					return $input[0].value = value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass('placeholder');
				input == safeActiveElement() && input.select();
			}
		}
	}

	function setPlaceholder() {
		var $replacement;
		var input = this;
		var $input = $(input);
		var id = this.id;
		if (input.value == '') {
			if (input.type == 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().attr({ 'type': 'text' });
					} catch(e) {
						$replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
					}
					$replacement
						.removeAttr('name')
						.data({
							'placeholder-password': $input,
							'placeholder-id': id
						})
						.bind('focus.placeholder', clearPlaceholder);
					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id
						})
						.before($replacement);
				}
				$input = $input.removeAttr('id').hide().prev().attr('id', id).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass('placeholder');
			$input[0].value = $input.attr('placeholder');
		} else {
			// $input.removeClass('placeholder');
		}
	}

	function safeActiveElement() {
		// Avoid IE9 `document.activeElement` of death
		// https://github.com/mathiasbynens/jquery-placeholder/pull/99
		try {
			return document.activeElement;
		} catch (err) {}
	}

}(this, document, jQuery));
