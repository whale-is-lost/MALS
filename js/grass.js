
(function() {
    const container = document.createElement('div');
    container.style.cssText = `
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        z-index: -1;
        pointer-events: none;
    `;
    
    const canvas = document.createElement('canvas');
    canvas.style.cssText = `
        display: block;
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
        image-rendering: -moz-crisp-edges;
        image-rendering: crisp-edges;
    `;
    
    container.appendChild(canvas);
    document.body.appendChild(container);
    
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = 100;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    class GrassPixel {
        constructor(x, baseY, layer) {
            this.x = x;
            this.baseY = baseY;
            this.layer = layer;
            this.offset = Math.random() * Math.PI * 5;
            this.speed = 0.008 + Math.random() * 0.04;
            this.amplitude = 1 + Math.random() * 4;
            this.pixelSize = 4;
            
            const greens = [
                '#bfe6a1', '#d3f5b1', '#dcffc1', '#b0d68f',
                '#a8cc7f', '#aedc8f', '#b6ec9f', '#8bbc6f',
                '#75ac5f', '#81af6a', '#92cc7f', '#66ac4f'
            ];
            
            this.color = greens[Math.floor(Math.random() * greens.length)];
        }
        
        update(time) {
            this.currentY = this.baseY + Math.sin(time * this.speed + this.offset) * this.amplitude;
        }
        
        draw() {
            ctx.fillStyle = this.color;
            ctx.fillRect(
                this.x,
                Math.floor(this.currentY),
                this.pixelSize,
                this.pixelSize
            );
        }
    }
    let grassPixels = [];
    
    function createGrass() {
        grassPixels = [];
        const pixelSize = 4;
    
        for (let x = 0; x < canvas.width; x += pixelSize) {
            const variation = Math.sin(x * 0.05) * 15 + Math.random() * 2;
            const baseHeight = 40 + variation;
            
            const numPixelsUp = Math.floor(baseHeight / pixelSize);
            
            for (let i = 0; i < numPixelsUp; i++) {
                const y = canvas.height - ((i + 1) * pixelSize);
                const layer = i;
                
                if (Math.random() > 0.1) {
                    grassPixels.push(new GrassPixel(x, y, layer));
                }
            }
            if (Math.random() > 0.5) {
                const tipY = canvas.height - (numPixelsUp * pixelSize) - pixelSize;
                grassPixels.push(new GrassPixel(x, tipY, numPixelsUp));
            }
        }
    }
    createGrass();
    
    const startTime = Date.now();
    
    function animate() {
        const currentTime = (Date.now() - startTime) / 50;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
ctx.fillStyle = 'rgba(129,175,106,0.5)';
        ctx.fillRect(0, canvas.height - 8, canvas.width, 8);
        
        grassPixels.forEach(pixel => {
            pixel.update(currentTime);
            pixel.draw();
        });
        
        requestAnimationFrame(animate);
    }
    animate();
    
    window.addEventListener('resize', () => {
        resizeCanvas();
        createGrass();
    });
})();