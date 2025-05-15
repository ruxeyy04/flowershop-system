"use strict"
const headerTopHigh=(selector)=>{const headerTop=document.querySelector(selector)
if(headerTop){const headerTopHeight=headerTop.clientHeight
const headerHeight=document.querySelector("main")
headerHeight.style.marginTop=headerTopHeight+"px"}}
headerTopHigh(".header-height")
const headerSticky=(selector)=>{const header=document.querySelector(selector)
if(header){const headerLogo=header.querySelector(".change-logo img")
window.addEventListener("scroll",()=>{const currentScroll=window.pageYOffset
if(currentScroll>150){header.classList.add("is-sticky")
if(headerLogo){headerLogo.src="./assets/images/logo.png"}}else{header.classList.remove("is-sticky")
if(headerLogo){headerLogo.src="./assets/images/logo-white.png"}}})}}
headerSticky(".header")
const getSiblings=(elem)=>{const siblings=[]
let sibling=elem.parentNode.firstChild
while(sibling){if(sibling.nodeType===1&&sibling!==elem){siblings.push(sibling)}
sibling=sibling.nextSibling}
return siblings}
const slideUp=(target,time)=>{const duration=time?time:500
target.style.transitionProperty="height, margin, padding"
target.style.transitionDuration=duration+"ms"
target.style.boxSizing="border-box"
target.style.height=target.offsetHeight+"px"
target.offsetHeight
target.style.overflow="hidden"
target.style.height=0
window.setTimeout(()=>{target.style.display="none"
target.style.removeProperty("height")
target.style.removeProperty("overflow")
target.style.removeProperty("transition-duration")
target.style.removeProperty("transition-property")},duration)}
const slideDown=(target,time)=>{const duration=time?time:500
target.style.removeProperty("display")
let display=window.getComputedStyle(target).display
if(display==="none")display="block"
target.style.display=display
const height=target.offsetHeight
target.style.overflow="hidden"
target.style.height=0
target.offsetHeight
target.style.boxSizing="border-box"
target.style.transitionProperty="height, margin, padding"
target.style.transitionDuration=duration+"ms"
target.style.height=height+"px"
window.setTimeout(()=>{target.style.removeProperty("height")
target.style.removeProperty("overflow")
target.style.removeProperty("transition-duration")
target.style.removeProperty("transition-property")},duration)}
const slideToggle=(target,time)=>{const duration=time?time:500
if(window.getComputedStyle(target).display==="none"){return slideDown(target,duration)}else{return slideUp(target,duration)}}
const offCanvasMenu=(selector)=>{const offCanvasNav=document.querySelector(selector)
offCanvasNav.querySelectorAll(".menu-expand").forEach((item)=>{item.addEventListener("click",(e)=>{e.preventDefault()
const parent=item.parentElement.parentElement
if(parent.classList.contains("active")){parent.classList.remove("active")
parent.querySelectorAll(".sub-menu, .mega-menu, .children").forEach((subMenu)=>{subMenu.parentElement.classList.remove("active")
slideUp(subMenu)})}else{parent.classList.add("active")
slideDown(item.parentElement.nextElementSibling)
getSiblings(parent).forEach((item)=>{item.classList.remove("active")
item.querySelectorAll(".sub-menu, .mega-menu, .children").forEach((subMenu)=>{subMenu.parentElement.classList.remove("active")
slideUp(subMenu)})})}})})}
offCanvasMenu(".navbar-mobile-menu, .slidedown-menu__menu")
var swiper=new Swiper(".slider-active .swiper",{parallax:!0,effect:"fade",loop:!0,speed:1200,pagination:{el:".slider-active .swiper-pagination",type:"bullets",clickable:!0,},navigation:{nextEl:".slider-active .swiper-button-next",prevEl:".slider-active .swiper-button-prev",},})
var swiper=new Swiper(".features-text .swiper",{loop:!0,slidesPerView:"auto",centeredSlides:!0,speed:18000,freeMode:{enabled:!0,momentumBounce:!1,},autoplay:{delay:1,disableOnInteraction:!1,},})
var swiper=new Swiper(".brand-active .swiper",{loop:!0,breakpoints:{0:{slidesPerView:2,spaceBetween:0,},576:{slidesPerView:3,spaceBetween:0,},768:{slidesPerView:4,spaceBetween:0,},992:{slidesPerView:5,spaceBetween:0,},},})
var swiper=new Swiper(".product-active .swiper",{loop:!0,navigation:{nextEl:".product-active .swiper-button-next",prevEl:".product-active .swiper-button-prev",},breakpoints:{0:{slidesPerView:1,spaceBetween:20,},576:{slidesPerView:2,spaceBetween:20,},768:{slidesPerView:3,spaceBetween:20,},992:{slidesPerView:3,spaceBetween:20,},1200:{slidesPerView:4,spaceBetween:30,},},})
var swiper=new Swiper(".instagram-active .swiper",{loop:!0,breakpoints:{0:{slidesPerView:1,spaceBetween:20,},576:{slidesPerView:2,spaceBetween:20,},768:{slidesPerView:3,spaceBetween:20,},992:{slidesPerView:3,spaceBetween:20,},1200:{slidesPerView:3,spaceBetween:30,},1600:{slidesPerView:4,spaceBetween:70,},},})
var swiper=new Swiper(".category-active .swiper",{loop:!0,navigation:{nextEl:".category-active .swiper-button-next",prevEl:".category-active .swiper-button-prev",},breakpoints:{0:{slidesPerView:2,spaceBetween:20,},576:{slidesPerView:2,spaceBetween:20,},768:{slidesPerView:3,spaceBetween:20,},992:{slidesPerView:3,spaceBetween:20,},1200:{slidesPerView:4,spaceBetween:0,},},})
var swiper=new Swiper(".gallery-active .swiper",{loop:!0,navigation:{nextEl:".gallery-active .swiper-button-next",prevEl:".gallery-active .swiper-button-prev",},})
var swiper=new Swiper(".brand-2-active .swiper",{loop:!0,navigation:{nextEl:".brand-2-active .swiper-button-next",prevEl:".brand-2-active .swiper-button-prev",},breakpoints:{0:{slidesPerView:2,spaceBetween:20,},576:{slidesPerView:3,spaceBetween:20,},992:{slidesPerView:4,spaceBetween:20,},1200:{slidesPerView:4,spaceBetween:0,},},})
var swiper=new Swiper(".client-active .swiper",{loop:!0,pagination:{el:".client-active .swiper-pagination",type:"bullets",clickable:!0,},breakpoints:{0:{slidesPerView:1,spaceBetween:20,},576:{slidesPerView:2,spaceBetween:20,},768:{slidesPerView:2,spaceBetween:20,},992:{slidesPerView:3,spaceBetween:20,},1200:{slidesPerView:3,spaceBetween:30,},1600:{slidesPerView:4,spaceBetween:36,},},})
const tooltipTriggerList=document.querySelectorAll('[data-bs-tooltip="tooltip"]')
const tooltipList=[...tooltipTriggerList].map((tooltipTriggerEl)=>new bootstrap.Tooltip(tooltipTriggerEl))
const instagramMasonry=(selector)=>{const grid=document.querySelector(selector)
if(grid){window.addEventListener("load",()=>{const msnry=new Masonry(grid,{itemSelector:".grid-item",})})}}
instagramMasonry(".grid")
const countdown=(selector)=>{const countdownSelector=document.querySelectorAll(selector)
countdownSelector.forEach((countdowns)=>{const setValue=countdowns.getAttribute("data-countdown")
const second=1000,minute=second*60,hour=minute*60,day=hour*24
const countDown=new Date(setValue).getTime()
const x=setInterval(()=>{const now=new Date().getTime()
const distance=countDown-now
const result=distance<0
const days=countdowns.querySelector(".days")
const hours=countdowns.querySelector(".hours")
const minutes=countdowns.querySelector(".minutes")
const seconds=countdowns.querySelector(".seconds")
days.innerText=result?"00":Math.floor(distance/day)>9?Math.floor(distance/day):"0"+Math.floor(distance/day)
hours.innerText=result?"00":Math.floor((distance%day)/hour)>9?Math.floor((distance%day)/hour):"0"+Math.floor((distance%day)/hour)
minutes.innerText=result?"00":Math.floor((distance%hour)/minute)>9?Math.floor((distance%hour)/minute):"0"+Math.floor((distance%hour)/minute)
seconds.innerText=result?"00":Math.floor((distance%minute)/second)>9?Math.floor((distance%minute)/second):"0"+Math.floor((distance%minute)/second)
if(result){clearInterval(x)}},0)})}
countdown(".countdown")
var testimonial=new Swiper(".testimonial-active .swiper",{slidesPerView:1,loop:!0,navigation:{nextEl:".testimonial-active  .swiper-button-next",prevEl:".testimonial-active  .swiper-button-prev",},})
const productColorSwitcher=(selector)=>{const productColorSwitcher=document.querySelector(selector)
if(productColorSwitcher){const colorItem=productColorSwitcher.querySelectorAll(".color-item")
colorItem.forEach((item)=>{item.addEventListener("click",(e)=>{e.preventDefault()
item.classList.add("active")
getSiblings(item).forEach((item)=>{item.classList.remove("active")})})})}}
productColorSwitcher(".single-product__info--color-swatch")
const lightboxVideo=GLightbox({selector:".glightbox",})
const productLightbox=GLightbox({selector:".product-glightbox",})
const shopFilter=(selector)=>{const shopFilter=document.querySelector(selector)
if(shopFilter){const shopFilterWidget=document.querySelector(".shop-filter-widget")
shopFilter.addEventListener("click",(e)=>{e.preventDefault()
if(shopFilterWidget.classList.contains("open")){shopFilterWidget.classList.remove("open")}else{shopFilterWidget.classList.add("open")}})}}
shopFilter(".shop-filter-toggle")
const priceRange=(selector)=>{const priceRange=document.querySelector(selector)
if(priceRange){const rangeInput=document.querySelectorAll(".filter-range-input input")
const priceInput=document.querySelectorAll(".filter-price-value input")
const priceinput=document.querySelectorAll(".filter-price-value input")
const range=document.querySelector(".filter-slider .filter-progress")
let priceGap=10
window.addEventListener("load",()=>{const minVal=parseInt(rangeInput[0].value);const maxVal=parseInt(rangeInput[1].value);const minPossibleVal=parseInt(rangeInput[0].min);const maxPossibleVal=parseInt(rangeInput[1].max);const leftPercent=((minVal-minPossibleVal)/(maxPossibleVal-minPossibleVal))*100;const rightPercent=100-((maxVal-minPossibleVal)/(maxPossibleVal-minPossibleVal))*100;range.style.left=leftPercent+"%";range.style.right=rightPercent+"%"})
priceInput.forEach((input)=>{input.addEventListener("input",(e)=>{let minPrice=parseInt(priceInput[0].value)
let maxPrice=parseInt(priceInput[1].value)
if(maxPrice-minPrice>=priceGap&&maxPrice<=rangeInput[1].max){if(e.target.className==="input-min"){rangeInput[0].value=minPrice
range.style.left=(minPrice/rangeInput[0].max)*100+"%"}else{rangeInput[1].value=maxPrice
range.style.right=100-(maxPrice/rangeInput[1].max)*100+"%"}}})})
rangeInput.forEach((input)=>{input.addEventListener("input",(e)=>{let minVal=parseInt(rangeInput[0].value)
let maxVal=parseInt(rangeInput[1].value)
const minPossibleVal=parseInt(rangeInput[0].min);const maxPossibleVal=parseInt(rangeInput[1].max);if(maxVal-minVal<priceGap){if(e.target.className==="range-min"){rangeInput[0].value=maxVal-priceGap}else{rangeInput[1].value=minVal+priceGap}}else{priceInput[0].value="₱"+minVal
priceInput[1].value="₱"+maxVal
range.style.left=((minVal-minPossibleVal)/(maxPossibleVal-minPossibleVal))*100+"%"
range.style.right=100-((maxVal-minPossibleVal)/(maxPossibleVal-minPossibleVal))*100+"%"}
let pricemerge="₱"+minVal+" - "+"₱"+maxVal
priceinput[2].value=pricemerge})})}}
priceRange(".price-range-filter")
const ProductThumb=new Swiper(".product-single-thumb .swiper",{spaceBetween:30,slidesPerView:4,freeMode:!0,watchSlidesProgress:!0,breakpoints:{0:{spaceBetween:10,},768:{spaceBetween:30,},},})
const ProductSingle=new Swiper(".product-single-slide .swiper",{spaceBetween:0,navigation:{nextEl:".product-single-slide .swiper-button-next",prevEl:".product-single-slide .swiper-button-prev",},thumbs:{swiper:ProductThumb,},})
var swiper=new Swiper(".related-product-active .swiper",{loop:!0,pagination:{el:".related-product-active .swiper-pagination",clickable:!0,},breakpoints:{0:{slidesPerView:1,spaceBetween:10,},768:{slidesPerView:3,spaceBetween:16,},992:{slidesPerView:4,spaceBetween:16,},1200:{slidesPerView:4,spaceBetween:16,},},})
const productVariable=(selector)=>{const productVariable=document.querySelector(selector)
if(productVariable){const productColor=productVariable.querySelectorAll(".variable-color__color")
const productSize=productVariable.querySelectorAll(".variable-size__size")
const reset=document.querySelector(".reset-variable")
const variableItem=[...productSize,...productColor]
variableItem.forEach((item)=>{item.addEventListener("click",(e)=>{e.preventDefault()
if(item.classList.contains("active")){item.classList.remove("active")
reset.classList.remove("visible")}else{item.classList.add("active")
reset.classList.add("visible")
getSiblings(item).forEach((item)=>{item.classList.remove("active")})}})
reset.addEventListener("click",(e)=>{e.preventDefault()
reset.classList.remove("visible")
item.classList.remove("active")})})}}
productVariable(".product-variable")
const productQuantity=(selector)=>{const quantity=document.querySelectorAll(selector)
quantity.forEach((element)=>{const quantityIncrease=element.querySelector(".increase")
const quantityDecrease=element.querySelector(".decrease")
const quantityInput=element.querySelector(".quantity-input")
let count=quantityInput.value
quantityIncrease.addEventListener("click",()=>{count++
count=count<10?count:count
quantityInput.value=count})
quantityDecrease.addEventListener("click",()=>{if(count>1){count--
count=count<10?count:count
quantityInput.value=count}})})}
productQuantity(".product-quantity")
var swiper=new Swiper(".product-single-carousel .swiper",{loop:!0,navigation:{nextEl:".product-single-carousel .swiper-button-next",prevEl:".product-single-carousel .swiper-button-prev",},breakpoints:{0:{slidesPerView:1,spaceBetween:0,},576:{slidesPerView:2,spaceBetween:20,},768:{slidesPerView:3,spaceBetween:30,},},})
document.addEventListener("DOMContentLoaded",(e)=>{const els=document.querySelectorAll(".select2")
els.forEach((select)=>{NiceSelect.bind(select,{searchable:!0,placeholder:"select",searchtext:"Search Country",selectedtext:"geselecteerd",})})})
const getHeight=(el)=>{let el_style=window.getComputedStyle(el)
let el_display=el_style.display
let el_position=el_style.position
let el_visibility=el_style.visibility
let el_max_height=el_style.maxHeight.replace("px","").replace("%","")
let wanted_height=0
if(el_display!=="none"&&el_max_height!=="0"){return el.offsetHeight}
el.style.position="absolute"
el.style.visibility="hidden"
el.style.display="block"
wanted_height=el.offsetHeight
el.style.display=el_display
el.style.position=el_position
el.style.visibility=el_visibility
return wanted_height}
const toggleSlide=(el)=>{let el_max_height=0
if(el.getAttribute("data-max-height")){if(el.style.maxHeight.replace("px","").replace("%","")==="0"){el.style.maxHeight=el.getAttribute("data-max-height")}else{el.style.maxHeight="0"}}else{el_max_height=getHeight(el)+"px"
el.style.transition="max-height 0.5s ease-in-out"
el.style.overflow="hidden"
el.style.maxHeight="0"
el.setAttribute("data-max-height",el_max_height)
el.style.display="block"
setTimeout(function(){el.style.maxHeight=el_max_height},10)}}
const checkoutAccount=(selector)=>{const checkoutVisible=document.querySelector(selector)
const account=document.querySelectorAll(".account")
if(checkoutVisible){account.forEach((element)=>{element.addEventListener("click",(e)=>{toggleSlide(checkoutVisible)})})}}
checkoutAccount(".checkout-account")
const checkoutShipping=(selector)=>{const checkoutVisible=document.querySelector(selector)
const shipping=document.querySelectorAll(".shipping")
if(checkoutVisible){shipping.forEach((element)=>{element.addEventListener("click",(e)=>{toggleSlide(checkoutVisible)})})}}
checkoutShipping(".checkout-shipping")
const QuickViewProduct=new Swiper(".quick-view-product-slide .swiper",{spaceBetween:0,navigation:{nextEl:".quick-view-product-slide .swiper-button-next",prevEl:".quick-view-product-slide .swiper-button-prev",},})
const scrollElements=document.querySelectorAll(".js-scroll")
const elementInView=(el,dividend=1)=>{const elementTop=el.getBoundingClientRect().top
return(elementTop<=(window.innerHeight||document.documentElement.clientHeight)/dividend)}
const elementOutofView=(el)=>{const elementTop=el.getBoundingClientRect().top
return(elementTop>(window.innerHeight||document.documentElement.clientHeight))}
const displayScrollElement=(element)=>{element.classList.add("scrolled")}
const hideScrollElement=(element)=>{element.classList.remove("scrolled")}
const handleScrollAnimation=()=>{scrollElements.forEach((el)=>{if(elementInView(el,1.25)){displayScrollElement(el)}else if(elementOutofView(el)){hideScrollElement(el)}})};["scroll","load"].forEach((el)=>{window.addEventListener(el,handleScrollAnimation)})
const AutoPopup=(selector)=>{const popup=document.querySelector(selector)
const popupOverlay=document.querySelector(".popup-modal-overlay")
const popupClose=document.querySelector(".popup-close__btn")
if(popup){setTimeout(()=>{popup.classList.add("open")
popup.classList.remove("close")
popupOverlay.classList.add("open")
popupOverlay.classList.remove("close")},1000);[popupClose,popupOverlay].forEach((el)=>{el.addEventListener("click",()=>{popup.classList.remove("open")
popup.classList.add("close")
popupOverlay.classList.remove("open")
popupOverlay.classList.add("close")})})}}
AutoPopup(".popup-modal")
const currentYear=(selector)=>{const yearSelector=document.querySelectorAll(selector)
const year=new Date().getFullYear()
yearSelector.forEach((curYear)=>{curYear.innerText=year.toString()})}
currentYear(".current-year")