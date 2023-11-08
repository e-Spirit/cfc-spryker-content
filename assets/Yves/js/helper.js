document.head.appendChild(document.createElement('style')).innerHTML = `
.fs-content-connect-slot {
    position: relative;
  }
  
  .fs-content-connect-editing-container {
    min-height: 6rem;
  }
  .fs-content-connect-editing-container .fs-content-connect-button-container {
    width: 100%;
    position: absolute;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
  }
  .fs-content-connect-editing-container .fs-content-connect-content-container {
    filter: contrast(50%);
  }
  .fs-content-connect-editing-container .fs-content-connect-content-container::after {
    display: block;
    clear: both;
    content: '';
  }
  .fs-content-connect-editing-container .fs-content-connect-content-container:hover {
    filter: contrast(70%);
    transition: all 0.2s ease-in-out;
  }
  @media screen and (prefers-reduced-motion: reduce) {
    .fs-content-connect-editing-container .fs-content-connect-content-container:hover {
      transition: none;
    }
  }
  .fs-content-connect-editing-container.fs-content-connect-highlight-content-area {
    border: 0.15rem transparent solid;
  }
  .fs-content-connect-editing-container.fs-content-connect-highlight-content-area:hover {
    background-color: rgba(108, 117, 125, 0.3);
  }
  .fs-content-connect-editing-container.fs-content-connect-highlight-content-area .fs-content-connect-button-container {
    position: relative;
    top: 0;
    transform: translateY(0%);
  }
  
  .fs-content-connect-editing-button,
  .fs-content-connect-editing-navigation-button-small,
  .fs-content-connect-editing-navigation-button-medium,
  .fs-content-connect-editing-navigation-button-large {
    display: block;
    width: auto;
    min-height: 3.65rem;
    min-width: 3.65rem;
    margin: 1rem auto;
    padding: 0 0.55rem;
    position: relative;
    top: 50%;
    color: #fff;
    font-size: 2.25rem;
    font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
    font-weight: 300;
    background-color: #3288c3;
    border-radius: 3.65rem;
    border: 0.15rem #3288c3 solid;
    cursor: pointer;
    -webkit-user-select: none;
    /* Safari */
    -moz-user-select: none;
    /* Firefox */
    -ms-user-select: none;
    /* IE10+/Edge */
    user-select: none;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon {
    color: #fff;
    margin-top: -0.3rem;
    box-sizing: border-box;
    display: inline-block;
    height: 1em;
    width: 1em;
    position: relative;
    font-size: inherit;
    font-style: normal;
    text-indent: -9999px;
    vertical-align: middle;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon::before,
  .fs-content-connect-editing-button .fs-content-connect-icon::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon::after {
    content: '';
    display: block;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-add::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-add::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-add::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-add::before {
    background: currentColor;
    height: 0.2rem;
    width: 100%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-add::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-add::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-add::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-add::after {
    background: currentColor;
    height: 100%;
    width: 0.2rem;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-delete::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-delete::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-delete::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-delete::before {
    border: 0.15rem solid currentColor;
    border-bottom-left-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
    border-top: 0;
    height: 0.75em;
    top: 60%;
    width: 0.75em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-delete::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-delete::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-delete::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-delete::after {
    background: currentColor;
    box-shadow: -0.25em 0.2em, 0.25em 0.2em;
    height: 0.15rem;
    top: 0.075rem;
    width: 0.5em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-bookmark::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-bookmark::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-bookmark::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-bookmark::before {
    border: 0.15rem solid currentColor;
    border-bottom: 0;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    height: 0.9em;
    width: 0.8em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-bookmark::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-bookmark::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-bookmark::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-bookmark::after {
    border: 0.15rem solid currentColor;
    border-bottom: 0;
    border-left: 0;
    border-radius: 0.25rem;
    height: 0.5em;
    transform: translate(-50%, 35%) rotate(-45deg) skew(15deg, 15deg);
    width: 0.5em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-copy::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-copy::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-copy::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-copy::before {
    border: 0.15rem solid currentColor;
    border-radius: 0.25rem;
    border-right: 0;
    border-bottom: 0;
    height: 0.8em;
    left: 40%;
    top: 35%;
    width: 0.8em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-copy::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-copy::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-copy::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-copy::after {
    border: 0.15rem solid currentColor;
    border-radius: 0.25rem;
    height: 0.8em;
    left: 60%;
    top: 60%;
    width: 0.8em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-edit::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-edit::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-edit::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-edit::before {
    border: 0.15rem solid currentColor;
    height: 0.4em;
    transform: translate(-44%, -63%) rotate(-45deg);
    width: 0.85em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-edit::after,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-edit::after,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-edit::after,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-edit::after {
    border: 0.15em solid currentColor;
    border-top-color: transparent;
    border-right-color: transparent;
    height: 0;
    left: 5%;
    top: 95%;
    transform: translate(0, -100%);
    width: 0;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-downward::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-back::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-forward::before,
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-upward::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-downward::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-back::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-forward::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-upward::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-downward::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-back::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-forward::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-upward::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-downward::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-back::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-forward::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-upward::before {
    border: 0.15rem solid currentColor;
    border-bottom: 0;
    border-right: 0;
    height: 0.65em;
    width: 0.65em;
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-down::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-down::before {
    transform: translate(-50%, -75%) rotate(225deg);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-left::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-left::before {
    transform: translate(-25%, -50%) rotate(-45deg);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-right::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-right::before {
    transform: translate(-75%, -50%) rotate(135deg);
  }
  .fs-content-connect-editing-button .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon.fs-content-connect-icon-up::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon.fs-content-connect-icon-up::before {
    transform: translate(-50%, -25%) rotate(45deg);
  }
  .fs-content-connect-editing-button .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-button-label {
    display: inline-block;
    max-width: 0;
    opacity: 0;
    white-space: nowrap;
    transition: 1s, opacity 0.2s;
  }
  @media screen and (prefers-reduced-motion: reduce) {
    .fs-content-connect-editing-button .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-small .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-medium .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-large .fs-content-connect-button-label {
      transition: none;
    }
  }
  .fs-content-connect-editing-button:hover,
  .fs-content-connect-editing-navigation-button-small:hover,
  .fs-content-connect-editing-navigation-button-medium:hover,
  .fs-content-connect-editing-navigation-button-large:hover {
    background-color: #266895;
    border-color: #266895;
  }
  .fs-content-connect-editing-button:hover .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-small:hover .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-medium:hover .fs-content-connect-button-label,
  .fs-content-connect-editing-navigation-button-large:hover .fs-content-connect-button-label {
    opacity: 1;
    padding: 0 0.8rem;
    max-width: 50rem;
    transition: max-width 2s ease-out 0.1s, opacity 1s ease-out 0.5s;
  }
  @media screen and (prefers-reduced-motion: reduce) {
    .fs-content-connect-editing-button:hover .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-small:hover .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-medium:hover .fs-content-connect-button-label,
    .fs-content-connect-editing-navigation-button-large:hover .fs-content-connect-button-label {
      transition: none;
    }
  }
  
  .fs-content-connect-editing-navigation-button-small {
    min-height: 0.2rem;
    min-width: 0.2rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
  }
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon-small {
    color: #fff;
    margin-bottom: 0.1rem;
    box-sizing: border-box;
    display: inline-block;
    height: 1em;
    width: 1em;
    position: relative;
    font-size: inherit;
    font-style: normal;
    text-indent: -9999px;
    vertical-align: middle;
  }
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon-small::before,
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon-small::after {
    content: '';
    display: block;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon-small.fs-content-connect-icon-add::before {
    background: currentColor;
    height: 0.2rem;
    width: 100%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-small .fs-content-connect-icon-small.fs-content-connect-icon-add::after {
    background: currentColor;
    height: 100%;
    width: 0.2rem;
    transform: translate(-50%, -50%);
  }
  
  .fs-content-connect-editing-navigation-button-medium {
    min-height: 0.25rem;
    min-width: 0.25rem;
    font-size: 1.5rem;
    border-radius: 0.25rem;
  }
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon-medium {
    color: #fff;
    margin-bottom: 0.1rem;
    box-sizing: border-box;
    display: inline-block;
    height: 1em;
    width: 1em;
    position: relative;
    font-size: inherit;
    font-style: normal;
    text-indent: -9999px;
    vertical-align: middle;
  }
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon-medium::before,
  .fs-content-connect-editing-navigation-button-medium .fs-content-connect-icon-medium::after {
    content: '';
    display: block;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-medium
    .fs-content-connect-icon-medium.fs-content-connect-icon-add::before {
    background: currentColor;
    height: 0.2rem;
    width: 100%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-medium
    .fs-content-connect-icon-medium.fs-content-connect-icon-add::after {
    background: currentColor;
    height: 100%;
    width: 0.2rem;
    transform: translate(-50%, -50%);
  }
  
  .fs-content-connect-editing-navigation-button-large {
    font-size: 2.25rem;
    border-radius: 0.3rem;
  }
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon-large {
    color: #fff;
    margin-bottom: 0.15rem;
    box-sizing: border-box;
    display: inline-block;
    height: 1em;
    width: 1em;
    position: relative;
    font-size: inherit;
    font-style: normal;
    text-indent: -9999px;
    vertical-align: middle;
  }
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon-large::before,
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon-large::after {
    content: '';
    display: block;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon-large.fs-content-connect-icon-add::before {
    background: currentColor;
    height: 0.2rem;
    width: 100%;
    transform: translate(-50%, -50%);
  }
  .fs-content-connect-editing-navigation-button-large .fs-content-connect-icon-large.fs-content-connect-icon-add::after {
    background: currentColor;
    height: 100%;
    width: 0.2rem;
    transform: translate(-50%, -50%);
  }
  
  .fs-section-blur {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 9001;
    cursor: not-allowed;
    background: rgba(187, 187, 187, 0.7);
  }
  
  .fs-section-spinner-wrapper {
    width: 2.5rem;
    height: 2.5rem;
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translateY(-50%) translateX(-50%);
    transform: translateY(-50%) translateX(-50%);
  }
  .fs-section-spinner-wrapper .fs-section-spinner {
    display: block;
    width: 100%;
    height: 100%;
    border: 0.375rem solid #fff;
    border-top: 0.375rem solid #3288c3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  `;

const __originalPosition = Symbol();

const DISABLED_CLASS_NAME = 'tpp-disabled-node';


const disable = (el = document.body, spinner = el !== document.body) => {
    if (spinner) {
        el[__originalPosition] = el.style.position;
        el.style.position = `relative`;
        el.insertAdjacentHTML(
            `beforeend`,
                `<div class="fs-section-blur">
                <div class="fs-section-spinner-wrapper">
                    <span class="fs-section-spinner"></span>
                </div>
                </div>`);
    } else {
        el.classList.toggle(DISABLED_CLASS_NAME, true);
    }
};

const enable = (el = document.body) => {
    if (__originalPosition in el) {
        el.style.position = el[__originalPosition];
        delete el[__originalPosition];
        const spinner = el.querySelector(`.fs-section-blur`);
        spinner && spinner.parentElement.removeChild(spinner);
    } else {
        el.classList.remove(DISABLED_CLASS_NAME);
    }
};