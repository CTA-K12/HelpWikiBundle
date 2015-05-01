(function() {
  CKEDITOR.on("dialogDefinition", function(definition) {
    var tab;
    tab = definition.data.name;
    definition = definition.data.definition;
    if ("link" == tab) {
      definition.removeContents("target");
      definition.removeContents("upload");
      definition.removeContents("advanced");
      tab = definition.getContents("info");
      tab.remove("emailSubject");
      tab.remove("emailBody");
    } else {
      if ("image" == tab) {
        definition.removeContents("advanced");
        tab = definition.getContents("Link");
        tab.remove("cmbTarget");
        tab = definition.getContents("info");
        tab.remove("txtAlt");
        tab.remove("basic");
      }
    }
  });
  var bbcodeMap = {
    b : "strong",
    u : "u",
    i : "em",
    color : "span",
    size : "span",
    quote : "blockquote",
    code : "code",
    url : "a",
    email : "span",
    img : "span",
    "*" : "li",
    list : "ol"
  };
  var convertMap = {
    strong : "b",
    b : "b",
    u : "u",
    em : "i",
    i : "i",
    code : "code",
    li : "*"
  };
  var tagnameMap = {
    strong : "b",
    em : "i",
    u : "u",
    li : "*",
    ul : "list",
    ol : "list",
    code : "code",
    a : "link",
    img : "img",
    blockquote : "quote"
  };
  var obj = {
    color : "color",
    size : "font-size"
  };
  var attributesMap = {
    url : "href",
    email : "mailhref",
    quote : "cite",
    list : "listType"
  };
  var dtd = CKEDITOR.dtd;
  var blockLikeTags = CKEDITOR.tools.extend({
    table : 1
  }, dtd.$block, dtd.$listItem, dtd.$tableContent, dtd.$list);

  /** @type {RegExp} */
  var r20 = /\s*(?:;\s*|$)/;
  
  var smileyMap = {
    smiley : ":)",
    sad : ":(",
    wink : ";)",
    laugh : ":D",
    cheeky : ":P",
    blush : ":*)",
    surprise : ":-o",
    indecision : ":|",
    angry : ">:(",
    angel : "o:)",
    cool : "8-)",
    devil : ">:-)",
    crying : ";(",
    kiss : ":-*"
  };
  var smileyReverseMap = {};

  /** @type {Array} */
  var text = [];
  var i;
  for (i in smileyMap) {
    /** @type {string} */
    smileyReverseMap[smileyMap[i]] = i;
    text.push(smileyMap[i].replace(/\(|\)|\:|\/|\*|\-|\|/g, function(dataAndEvents) {
      return "\\" + dataAndEvents;
    }));
  }
  
  /** @type {RegExp} */
  text = RegExp(text.join("|"), "g");
  var decodeHtml = function() {
    /** @type {Array} */
    var text = [];
    var entities = {
      nbsp : "\u00a0",
      shy : "\u00ad",
      gt : ">",
      lt : "<"
    };
    var entity;
    for (entity in entities) {
      text.push(entity);
    }
    /** @type {RegExp} */
    text = RegExp("&(" + text.join("|") + ");", "g");
    return function(block) {
      return block.replace(text, function(dataAndEvents, capture) {
        return entities[capture];
      });
    };
  }();
  
  /**
   * @return {undefined}
   */
  CKEDITOR.BBCodeParser = function() {
    this._ = {
      bbcPartsRegex : /(?:\[([^\/\]=]*?)(?:=([^\]]*?))?\])|(?:\[\/([a-z]{1,16})\])/ig
    };
  };
  CKEDITOR.BBCodeParser.prototype = {
    /**
     * @param {string} html
     * @return {undefined}
     */
    parse : function(html) {
      var part;
      var i;
      /** @type {number} */
      var nextIndex = 0;
      for (;part = this._.bbcPartsRegex.exec(html);) {
        i = part.index;
        if (i > nextIndex) {
          this.onText(html.substring(nextIndex, i), 1);
        }
        nextIndex = this._.bbcPartsRegex.lastIndex;
        if ((i = (part[1] || (part[3] || "")).toLowerCase()) && !bbcodeMap[i]) {
          this.onText(part[0]);
        } else {
          if (part[1]) {
            var tagName = bbcodeMap[i];
            var attribs = {};
            var result = {};
            if (part = part[2]) {
              if ("list" == i && (isNaN(part) ? /^[a-z]+$/.test(part) ? part = "lower-alpha" : /^[A-Z]+$/.test(part) && (part = "upper-alpha") : part = "decimal"), obj[i]) {
                if ("size" == i) {
                  part += "%";
                }
                result[obj[i]] = part;
                part = attribs;
                /** @type {string} */
                var right = "";
                var attr = void 0;
                for (attr in result) {
                  var left = (attr + ":" + result[attr]).replace(r20, ";");
                  /** @type {string} */
                  right = right + left;
                }
                /** @type {string} */
                part.style = right;
              } else {
                if (attributesMap[i]) {
                  attribs[attributesMap[i]] = part;
                }
              }
            }
            if ("email" == i || "img" == i) {
              attribs.bbcode = i;
            }
            this.onTagOpen(tagName, attribs, CKEDITOR.dtd.$empty[tagName]);
          } else {
            if (part[3]) {
              this.onTagClose(bbcodeMap[i]);
            }
          }
        }
      }
      if (html.length > nextIndex) {
        this.onText(html.substring(nextIndex, html.length), 1);
      }
    }
  };
  /**
   * @param {Object} node
   * @return {?}
   */
  CKEDITOR.htmlParser.fragment.fromBBCode = function(node) {
    /**
     * @param {?} newTagName
     * @return {undefined}
     */
    function checkPending(newTagName) {
      if (0 < matches.length) {
        /** @type {number} */
        var i = 0;
        for (;i < matches.length;i++) {
          var pendingElement = matches[i];
          var pendingName = pendingElement.name;
          var pendingDtd = CKEDITOR.dtd[pendingName];
          var currentDtd = currentNode.name && CKEDITOR.dtd[currentNode.name];
          if ((!currentDtd || currentDtd[pendingName]) && (!newTagName || (!pendingDtd || (pendingDtd[newTagName] || !CKEDITOR.dtd[newTagName])))) {
            pendingElement = pendingElement.clone();
            pendingElement.parent = currentNode;
            currentNode = pendingElement;
            matches.splice(i, 1);
            i--;
          }
        }
      }
    }
    /**
     * @param {Array} tagName
     * @param {boolean} closing
     * @return {undefined}
     */
    function checkPendingBrs(tagName, closing) {
      var len = currentNode.children.length;
      var previous = 0 < len && currentNode.children[len - 1];
      len = !previous && BBCodeWriter.getRule(tagnameMap[currentNode.name], "breakAfterOpen");
      previous = previous && (previous.type == CKEDITOR.NODE_ELEMENT && BBCodeWriter.getRule(tagnameMap[previous.name], "breakAfterClose"));
      var lineBreakCurrent = tagName && BBCodeWriter.getRule(tagnameMap[tagName], closing ? "breakBeforeClose" : "breakBeforeOpen");
      if (top) {
        if (len || (previous || lineBreakCurrent)) {
          top--;
        }
      }
      if (top) {
        if (tagName in blockLikeTags) {
          top++;
        }
      }
      for (;top && top--;) {
        currentNode.children.push(new CKEDITOR.htmlParser.element("br"));
      }
    }
    /**
     * @param {Object} node
     * @param {Object} target
     * @return {undefined}
     */
    function addElement(node, target) {
      checkPendingBrs(node.name, 1);
      target = target || (currentNode || fragment);
      var len = target.children.length;
      node.previous = 0 < len && target.children[len - 1] || null;
      /** @type {Object} */
      node.parent = target;
      target.children.push(node);
      if (node.returnPoint) {
        currentNode = node.returnPoint;
        delete node.returnPoint;
      }
    }
    var parser = new CKEDITOR.BBCodeParser;
    var fragment = new CKEDITOR.htmlParser.fragment;
    /** @type {Array} */
    var matches = [];
    /** @type {number} */
    var top = 0;
    var currentNode = fragment;
    var contents;
    /**
     * @param {?} tagName
     * @param {?} attributes
     * @param {?} selfClosing
     * @return {undefined}
     */
    parser.onTagOpen = function(tagName, attributes, selfClosing) {
      var node = new CKEDITOR.htmlParser.element(tagName, attributes);
      if (CKEDITOR.dtd.$removeEmpty[tagName]) {
        matches.push(node);
      } else {
        var currentName = currentNode.name;
        var currentDtd = currentName && (CKEDITOR.dtd[currentName] || (currentNode._.isBlockLike ? CKEDITOR.dtd.div : CKEDITOR.dtd.span));
        if (currentDtd && !currentDtd[tagName]) {
          /** @type {boolean} */
          currentDtd = false;
          var target;
          if (tagName == currentName) {
            addElement(currentNode, currentNode.parent);
          } else {
            if (tagName in CKEDITOR.dtd.$listItem) {
              parser.onTagOpen("ul", {});
              target = currentNode;
            } else {
              addElement(currentNode, currentNode.parent);
              matches.unshift(currentNode);
            }
            /** @type {boolean} */
            currentDtd = true;
          }
          currentNode = target ? target : currentNode.returnPoint || currentNode.parent;
          if (currentDtd) {
            parser.onTagOpen.apply(this, arguments);
            return;
          }
        }
        checkPending(tagName);
        checkPendingBrs(tagName);
        node.parent = currentNode;
        node.returnPoint = contents;
        /** @type {number} */
        contents = 0;
        if (node.isEmpty) {
          addElement(node);
        } else {
          currentNode = node;
        }
      }
    };
    /**
     * @param {Object} tagName
     * @return {undefined}
     */
    parser.onTagClose = function(tagName) {
      /** @type {number} */
      var i = matches.length - 1;
      for (;0 <= i;i--) {
        if (tagName == matches[i].name) {
          matches.splice(i, 1);
          return;
        }
      }
      /** @type {Array} */
      var codeSegments = [];
      /** @type {Array} */
      var result = [];
      var candidate = currentNode;
      for (;candidate.type && candidate.name != tagName;) {
        if (!candidate._.isBlockLike) {
          result.unshift(candidate);
        }
        codeSegments.push(candidate);
        candidate = candidate.parent;
      }
      if (candidate.type) {
        /** @type {number} */
        i = 0;
        for (;i < codeSegments.length;i++) {
          tagName = codeSegments[i];
          addElement(tagName, tagName.parent);
        }
        currentNode = candidate;
        addElement(candidate, candidate.parent);
        if (candidate == currentNode) {
          currentNode = currentNode.parent;
        }
        matches = matches.concat(result);
      }
    };
    /**
     * @param {string} text
     * @return {undefined}
     */
    parser.onText = function(text) {
      var currentDtd = CKEDITOR.dtd[currentNode.name];
      if (!currentDtd || currentDtd["#"]) {
        checkPendingBrs();
        checkPending();
        text.replace(/(\r\n|[\r\n])|[^\r\n]*/g, function(piece, newlines) {
          if (void 0 !== newlines && newlines.length) {
            top++;
          } else {
            if (piece.length) {
              /** @type {number} */
              var lastIndex = 0;
              piece.replace(text, function(match, index) {
                addElement(new CKEDITOR.htmlParser.text(piece.substring(lastIndex, index)), currentNode);
                addElement(new CKEDITOR.htmlParser.element("smiley", {
                  desc : smileyReverseMap[match]
                }), currentNode);
                lastIndex = index + match.length;
              });
              if (lastIndex != piece.length) {
                addElement(new CKEDITOR.htmlParser.text(piece.substring(lastIndex, piece.length)), currentNode);
              }
            }
          }
        });
      }
    };
    parser.parse(CKEDITOR.tools.htmlEncode(node));
    for (;currentNode.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT;) {
      node = currentNode.parent;
      addElement(currentNode, node);
      /** @type {Object} */
      currentNode = node;
    }
    return fragment;
  };
  var BBCodeWriter = new (CKEDITOR.tools.createClass({
    /**
     * @return {undefined}
     */
    $ : function() {
      this._ = {
        output : [],
        rules : []
      };
      this.setRules("list", {
        breakBeforeOpen : 1,
        breakAfterOpen : 1,
        breakBeforeClose : 1,
        breakAfterClose : 1
      });
      this.setRules("*", {
        breakBeforeOpen : 1,
        breakAfterOpen : 0,
        breakBeforeClose : 1,
        breakAfterClose : 0
      });
      this.setRules("quote", {
        breakBeforeOpen : 1,
        breakAfterOpen : 0,
        breakBeforeClose : 0,
        breakAfterClose : 1
      });
    },
    proto : {
      /**
       * @param {string} tagName
       * @param {Object} rules
       * @return {undefined}
       */
      setRules : function(tagName, rules) {
        var currentRules = this._.rules[tagName];
        if (currentRules) {
          CKEDITOR.tools.extend(currentRules, rules, true);
        } else {
          /** @type {Object} */
          this._.rules[tagName] = rules;
        }
      },
      /**
       * @param {?} tagName
       * @param {string} ruleName
       * @return {?}
       */
      getRule : function(tagName, ruleName) {
        return this._.rules[tagName] && this._.rules[tagName][ruleName];
      },
      /**
       * @param {?} tag
       * @return {undefined}
       */
      openTag : function(tag) {
        if (tag in bbcodeMap) {
          if (this.getRule(tag, "breakBeforeOpen")) {
            this.lineBreak(1);
          }
          this.write("[", tag);
        }
      },
      /**
       * @param {string} tag
       * @return {undefined}
       */
      openTagClose : function(tag) {
        if ("br" == tag) {
          this._.output.push("\n");
        } else {
          if (tag in bbcodeMap) {
            this.write("]");
            if (this.getRule(tag, "breakAfterOpen")) {
              this.lineBreak(1);
            }
          }
        }
      },
      /**
       * @param {string} value
       * @param {string} val
       * @return {undefined}
       */
      attribute : function(value, val) {
        if ("option" == value) {
          if ("string" == typeof val) {
            /** @type {string} */
            val = val.replace(/&amp;/g, "&");
          }
          this.write("=", val);
        }
      },
      /**
       * @param {string} tag
       * @return {undefined}
       */
      closeTag : function(tag) {
        if (tag in bbcodeMap) {
          if (this.getRule(tag, "breakBeforeClose")) {
            this.lineBreak(1);
          }
          if ("*" != tag) {
            this.write("[/", tag, "]");
          }
          if (this.getRule(tag, "breakAfterClose")) {
            this.lineBreak(1);
          }
        }
      },
      /**
       * @param {?} text
       * @return {undefined}
       */
      text : function(text) {
        this.write(text);
      },
      /**
       * @return {undefined}
       */
      comment : function() {
      },
      /**
       * @return {undefined}
       */
      lineBreak : function() {
        if (!this._.hasLineBreak) {
          if (this._.output.length) {
            this.write("\n");
            /** @type {number} */
            this._.hasLineBreak = 1;
          }
        }
      },
      /**
       * @return {undefined}
       */
      write : function() {
        /** @type {number} */
        this._.hasLineBreak = 0;
        this._.output.push(Array.prototype.join.call(arguments, ""));
      },
      /**
       * @return {undefined}
       */
      reset : function() {
        /** @type {Array} */
        this._.output = [];
        /** @type {number} */
        this._.hasLineBreak = 0;
      },
      /**
       * @param {boolean} dataAndEvents
       * @return {?}
       */
      getHtml : function(dataAndEvents) {
        var bbcode = this._.output.join("");
        if (dataAndEvents) {
          this.reset();
        }
        return decodeHtml(bbcode);
      }
    }
  }));
  CKEDITOR.plugins.add("bbcode", {
    requires : "entities",
    /**
     * @param {Object} editor
     * @return {undefined}
     */
    beforeInit : function(editor) {
      CKEDITOR.tools.extend(editor.config, {
        enterMode : CKEDITOR.ENTER_BR,
        basicEntities : false,
        entities : false,
        fillEmptyBlocks : false
      }, true);
      editor.filter.disable();
      editor.activeEnterMode = editor.enterMode = CKEDITOR.ENTER_BR;
    },
    /**
     * @param {Object} editor
     * @return {undefined}
     */
    init : function(editor) {
      /**
       * @param {string} node
       * @return {undefined}
       */
      function BBCodeToHtml(node) {
        var data = node.data;
        node = CKEDITOR.htmlParser.fragment.fromBBCode(node.data.dataValue);
        var writer = new CKEDITOR.htmlParser.basicWriter;
        node.writeHtml(writer, filter);
        node = writer.getHtml(true);
        /** @type {string} */
        data.dataValue = node;
      }
      var config = editor.config;
      var filter = new CKEDITOR.htmlParser.filter;
      filter.addRules({
        elements : {
          /**
           * @param {Object} element
           * @return {undefined}
           */
          blockquote : function(element) {
            var quoted = new CKEDITOR.htmlParser.element("div");
            quoted.children = element.children;
            /** @type {Array} */
            element.children = [quoted];
            if (quoted = element.attributes.cite) {
              var cite = new CKEDITOR.htmlParser.element("cite");
              cite.add(new CKEDITOR.htmlParser.text(quoted.replace(/^"|"$/g, "")));
              delete element.attributes.cite;
              element.children.unshift(cite);
            }
          },
          /**
           * @param {Object} element
           * @return {undefined}
           */
          span : function(element) {
            var bbcode;
            if (bbcode = element.attributes.bbcode) {
              if ("img" == bbcode) {
                /** @type {string} */
                element.name = "img";
                element.attributes.src = element.children[0].value;
                /** @type {Array} */
                element.children = [];
              } else {
                if ("email" == bbcode) {
                  /** @type {string} */
                  element.name = "a";
                  /** @type {string} */
                  element.attributes.href = "mailto:" + element.children[0].value;
                }
              }
              delete element.attributes.bbcode;
            }
          },
          /**
           * @param {Object} element
           * @return {undefined}
           */
          ol : function(element) {
            if (element.attributes.listType) {
              if ("decimal" != element.attributes.listType) {
                /** @type {string} */
                element.attributes.style = "list-style-type:" + element.attributes.listType;
              }
            } else {
              /** @type {string} */
              element.name = "ul";
            }
            delete element.attributes.listType;
          },
          /**
           * @param {Object} node
           * @return {undefined}
           */
          a : function(node) {
            if (!node.attributes.href) {
              node.attributes.href = node.children[0].value;
            }
          },
          /**
           * @param {Object} element
           * @return {undefined}
           */
          smiley : function(element) {
            /** @type {string} */
            element.name = "img";
            var description = element.attributes.desc;
            var image = config.smiley_images[CKEDITOR.tools.indexOf(config.smiley_descriptions, description)];
            image = CKEDITOR.tools.htmlEncode(config.smiley_path + image);
            element.attributes = {
              src : image,
              "data-cke-saved-src" : image,
              title : description,
              alt : description
            };
          }
        }
      });
      editor.dataProcessor.htmlFilter.addRules({
        elements : {
          /**
           * @param {Object} element
           * @return {?}
           */
          $ : function(element) {
            var attributes = element.attributes;
            var style = CKEDITOR.tools.parseCssText(attributes.style, 1);
            var value;
            var tagName = element.name;
            if (tagName in convertMap) {
              tagName = convertMap[tagName];
            } else {
              if ("span" == tagName) {
                if (value = style.color) {
                  /** @type {string} */
                  tagName = "color";
                  value = CKEDITOR.tools.convertRgbToHex(value);
                } else {
                  if (value = style["font-size"]) {
                    if (attributes = value.match(/(\d+)%$/)) {
                      value = attributes[1];
                      /** @type {string} */
                      tagName = "size";
                    }
                  }
                }
              } else {
                if ("ol" == tagName || "ul" == tagName) {
                  if (value = style["list-style-type"]) {
                    switch(value) {
                      case "lower-alpha":
                        /** @type {string} */
                        value = "a";
                        break;
                      case "upper-alpha":
                        /** @type {string} */
                        value = "A";
                    }
                  } else {
                    if ("ol" == tagName) {
                      /** @type {number} */
                      value = 1;
                    }
                  }
                  /** @type {string} */
                  tagName = "list";
                } else {
                  if ("blockquote" == tagName) {
                    try {
                      var datum = element.children[0];
                      var quoted = element.children[1];
                      var k = "cite" == datum.name && datum.children[0].value;
                      if (k) {
                        /** @type {string} */
                        value = '"' + k + '"';
                        element.children = quoted.children;
                      }
                    } catch (m) {
                    }
                    /** @type {string} */
                    tagName = "quote";
                  } else {
                    if ("a" == tagName) {
                      if (value = attributes.href) {
                        if (-1 !== value.indexOf("mailto:")) {
                          /** @type {string} */
                          tagName = "email";
                          /** @type {Array} */
                          element.children = [new CKEDITOR.htmlParser.text(value.replace("mailto:", ""))];
                          /** @type {string} */
                          value = "";
                        } else {
                          if (tagName = 1 == element.children.length && element.children[0]) {
                            if (tagName.type == CKEDITOR.NODE_TEXT && tagName.value == value) {
                              /** @type {string} */
                              value = "";
                            }
                          }
                          /** @type {string} */
                          tagName = "url";
                        }
                      }
                    } else {
                      if ("img" == tagName) {
                        /** @type {number} */
                        element.isEmpty = 0;
                        style = attributes["data-cke-saved-src"] || attributes.src;
                        attributes = attributes.alt;
                        if (style && (-1 != style.indexOf(editor.config.smiley_path) && attributes)) {
                          return new CKEDITOR.htmlParser.text(smileyMap[attributes]);
                        }
                        /** @type {Array} */
                        element.children = [new CKEDITOR.htmlParser.text(style)];
                      }
                    }
                  }
                }
              }
            }
            element.name = tagName;
            if (value) {
              element.attributes.option = value;
            }
            return null;
          },
          /**
           * @param {Object} next
           * @return {?}
           */
          br : function(next) {
            if ((next = next.next) && next.name in blockLikeTags) {
              return false;
            }
          }
        }
      }, 1);
      editor.dataProcessor.writer = BBCodeWriter;
      if (editor.elementMode == CKEDITOR.ELEMENT_MODE_INLINE) {
        editor.once("contentDom", function() {
          editor.on("setData", BBCodeToHtml);
        });
      } else {
        editor.on("setData", BBCodeToHtml);
      }
    },
    /**
     * @param {Object} editor
     * @return {undefined}
     */
    afterInit : function(editor) {
      var filters;
      if (editor._.elementsPath) {
        if (filters = editor._.elementsPath.filters) {
          filters.push(function(element) {
            var htmlName = element.getName();
            var name = tagnameMap[htmlName] || false;
            if ("link" == name && 0 === element.getAttribute("href").indexOf("mailto:")) {
              /** @type {string} */
              name = "email";
            } else {
              if ("span" == htmlName) {
                if (element.getStyle("font-size")) {
                  /** @type {string} */
                  name = "size";
                } else {
                  if (element.getStyle("color")) {
                    /** @type {string} */
                    name = "color";
                  }
                }
              } else {
                if ("img" == name) {
                  if (element = element.data("cke-saved-src") || element.getAttribute("src")) {
                    if (0 === element.indexOf(editor.config.smiley_path)) {
                      /** @type {string} */
                      name = "smiley";
                    }
                  }
                }
              }
            }
            return name;
          });
        }
      }
    }
  });
})();
