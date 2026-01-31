function openPopup(popupId) {
    document.getElementById(popupId).classList.add('active');
}

function closePopup(popupId) {
    document.getElementById(popupId).classList.remove('active');
}

let isDragging = false;
let currentX = 0;
let currentY = 0;
let initialX = 0;
let initialY = 0;
let xOffset = 0;
let yOffset = 0;

const draggableWindow = document.getElementById('draggable-window');
const header = document.querySelector('.popup-header');

if (header && draggableWindow) {
    header.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);
}

function dragStart(e) {
    if (e.target.classList.contains('close-btn')) return;
    
    initialX = e.clientX - xOffset;
    initialY = e.clientY - yOffset;
    isDragging = true;
}

function drag(e) {
    if (isDragging) {
        e.preventDefault();
        currentX = e.clientX - initialX;
        currentY = e.clientY - initialY;
        xOffset = currentX;
        yOffset = currentY;
        
        setTranslate(currentX, currentY, draggableWindow);
    }
}

function dragEnd(e) {
    initialX = currentX;
    initialY = currentY;
    isDragging = false;
}

function setTranslate(xPos, yPos, el) {
    el.style.transform = `translate(calc(-50% + ${xPos}px), calc(-50% + ${yPos}px))`;
}