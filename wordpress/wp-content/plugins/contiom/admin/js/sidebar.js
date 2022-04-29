/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!******************************************!*\
  !*** ./src/block-mynamespace-myblock.js ***!
  \******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const {
  registerPlugin
} = wp.plugins;
const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PluginSidebarMoreMenuItem,
  PluginSidebar
} = wp.editPost;
const {
  PanelBody,
  PanelHeader,
  PanelRow,
  ToggleControl,
  TabPanel,
  SelectControl
} = wp.components;
const {
  compose
} = wp.compose;
const {
  withDispatch,
  withSelect
} = wp.data;
const {
  useState
} = wp.element;

const MySelectControl = () => {
  const [project, setProject] = useState('50%');
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
    label: "Project",
    name: "prokects",
    labelPosition: "side",
    value: project,
    options: [{
      label: 'Auto complet project name',
      value: '-1'
    }],
    onChange: newProject => setProject(newProject)
  });
};

const CustomSidebarMetaComponent = props => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
    label: __('My custom post meta', 'contiom'),
    checked: props.customPostMetaValue,
    onChange: props.setCustomPostMeta
  });
};

const CustomSidebarMeta = compose([withSelect(select => {
  return {
    customPostMetaValue: select('core/editor').getEditedPostAttribute('meta')['contiom_my_custom_meta']
  };
}), withDispatch(dispatch => {
  return {
    setCustomPostMeta: function (value) {
      dispatch('core/editor').editPost({
        meta: {
          contiom_my_custom_meta: value
        }
      });
    }
  };
})])(CustomSidebarMetaComponent);

const CustomSidebarComponent = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebarMoreMenuItem, {
    target: "contiom-custom-sidebar",
    icon: "carrot"
  }, __('contiom', 'contiom')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebar, {
    name: "contiom-custom-sidebar",
    title: __('contiom', 'contiom')
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: __('contiom WP', 'contiom'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(MySelectControl, null))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: __('Advance Mode', 'contiom'),
    initialOpen: false
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TabPanel, {
    className: "my-tab-panel",
    activeClass: "active-tab",
    tabs: [{
      name: 'tab1',
      title: 'Tab 1',
      className: 'tab-one'
    }, {
      name: 'tab2',
      title: 'Tab 2',
      className: 'tab-two'
    }]
  }, tab => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, tab.title))))));
};

registerPlugin('contiom-customsidebar', {
  render: CustomSidebarComponent,
  icon: 'carrot'
});
}();
/******/ })()
;
//# sourceMappingURL=block-mynamespace-myblock.js.map