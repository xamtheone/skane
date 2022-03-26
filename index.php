<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>snake</title>
    <style>
      body, html {
        padding: 0;
        margin: 0;
        font-family: 'Courier New', Courier, monospace;
      }
      canvas {
        display: block;
        width: 500px;
        height: 500px;
      }
      .game-over {
        display: none;
        color: #ddd;
        width: 500px;
        height: 500px;
        background: #000;
        align-items: center;
        justify-content: center;
        font-size: 50px;
      }
    </style>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        startAnimating(10);
      });

      var v = {x: 0, y: 0};
      var pos = {x: 0, y: 0};
      var foodPos = null;
      var snake = [{x: 0, y: 0}];
      var prevPos = {x: 0, y: 0};
      var gameOver = false;

      window.addEventListener("keydown", event => {
        if (event.isComposing || event.keyCode === 229) {
          return;
        }
        
        switch (event.code) {
          case "ArrowUp":
            if (v.y != 0) {
              break;
            }
            v.x = 0;
            v.y = -1;
            break;
          case "ArrowRight":
            if (v.x != 0) {
              break;
            }
            v.x = 1;
            v.y = 0;
            break;
          case "ArrowDown":
            if (v.y != 0) {
              break;
            }
            v.x = 0;
            v.y = 1;
            break;
          case "ArrowLeft":
            if (v.x != 0) {
              break;
            }
            v.x = -1;
            v.y = 0;
            break;
        }
      });

      function render() {
        let gridSize = 30;
        const canvas = document.querySelector('canvas');
        const c = canvas.getContext('2d');
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        let snakeBlocSize = Math.floor(canvas.width / gridSize);
        let ateSelf = false;

        if (gameOver) {
          return;
        }

        for (let part of snake) {
          if (part != snake[0] && part.x == snake[0].x && part.y == snake[0].y) {
            ateSelf = true;
            break;
          }
        }

        if (ateSelf || snake[0].x < 0 || snake[0].y < 0 || snake[0].x >= canvas.width || snake[0].y >= canvas.height) {
          gameOver = true;
          canvas.style.display = "none";
          document.querySelector('.game-over').style.display = "flex";
          return;
        }

        // background
        c.fillStyle = '#000';
        c.fillRect(0, 0, canvas.width, canvas.height);

        prevPos = {...snake[-1]};

        for (let i = snake.length - 1; i > 0; i--) {
          snake[i].x = snake[i-1].x;
          snake[i].y = snake[i-1].y;
        }

        snake[0].x += v.x * snakeBlocSize;
        snake[0].y += v.y * snakeBlocSize;

        if (foodPos != null && foodPos.x == snake[0].x && foodPos.y == snake[0].y) {
          growSnake();
        }

        if (foodPos == null || foodPos.x == snake[0].x && foodPos.y == snake[0].y) {
          foodPos = {
            x: Math.floor(Math.random() * gridSize) * snakeBlocSize,
            y: Math.floor(Math.random() * gridSize) * snakeBlocSize
          };
        }

        drawSnake(c, snakeBlocSize);
        drawFood(c, snakeBlocSize);
      }

      function drawSnake(c, size) {
        c.fillStyle = "#ddd";
        for (let part of snake) {
          c.fillRect(part.x, part.y, size, size);
        }
      }

      function drawFood(c, size) {
        c.fillStyle = "#e00";
        c.fillRect(foodPos.x, foodPos.y, size, size);
      }

      function growSnake() {
        snake.push(prevPos);
      }


      // animation fps control
      var fps, fpsInterval, startTime, now, then, elapsed;
    
      function startAnimating(fps) {
        fpsInterval = 1000 / fps;
        then = window.performance.now();
        startTime = then;
        animate();
      }
    
      function animate() {
        requestAnimationFrame(animate);

        now = window.performance.now();
        elapsed = now - then;

        if (elapsed > fpsInterval) {
          then = now - (elapsed % fpsInterval);
          render();
        }
      }
    </script>
  </head>
  <body>
    <canvas></canvas>
    <div class="game-over">GAME OVER</div>

    <a href="/bananaskate">Banana skate</a>
  </body>
</html>