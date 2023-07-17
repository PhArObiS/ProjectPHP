document.addEventListener("DOMContentLoaded", function() {
    const levelTitle = document.getElementById("levelTitle");
    const levelDescription = document.getElementById("levelDescription");
    const lettersToArrange = document.getElementById("lettersToArrange");
  
    const levels = [
      { 
        title: "Level 1 - Arrange Letters in Ascending Order",
        description: "Arrange the letters in ascending order:",
        expectedAnswer: "ABCDEF"
      },
      { 
        title: "Level 2 - Arrange Letters in Descending Order",
        description: "Arrange the letters in descending order:",
        expectedAnswer: "FEDCBA"
      },
      { 
        title: "Level 3 - Arrange Numbers in Ascending Order",
        description: "Arrange the numbers in ascending order:",
        expectedAnswer: "123456"
      },
      { 
        title: "Level 4 - Arrange Numbers in Descending Order",
        description: "Arrange the numbers in descending order:",
        expectedAnswer: "654321"
      },
      { 
        title: "Level 5 - Identify the First and Last Letter",
        description: "Identify the first and last letter:",
        expectedAnswer: "AF"
      },
      { 
        title: "Level 6 - Identify the Smallest and Largest Number",
        description: "Identify the smallest and largest number:",
        expectedAnswer: "16"
      }
    ];
  
    function updateForm(levelIndex) {
      const level = levels[levelIndex];
      levelTitle.textContent = level.title;
      levelDescription.textContent = level.description;
      lettersToArrange.textContent = level.expectedAnswer;
    }
  
    document.getElementById("gameForm").addEventListener("submit", function(event) {
      event.preventDefault();
  
      const userInput = document.getElementById("inputField").value;
      const levelIndex = parseInt(document.getElementById("levelIndex").value);
      const expectedAnswer = levels[levelIndex].expectedAnswer;
  
      if (userInput === expectedAnswer) {
        if (levelIndex === levels.length - 1) {
          alert("Congratulations! You completed all levels successfully.");
          restartGame();
        } else {
          alert("Congratulations! You completed the level successfully.");
          updateForm(levelIndex + 1);
          document.getElementById("levelIndex").value = levelIndex + 1;
          document.getElementById("inputField").value = "";
        }
      } else {
        alert("Oops! Your arrangement is incorrect. Please try again.");
      }
    });
  
    document.getElementById("giveUpButton").addEventListener("click", function() {
      if (confirm("Are you sure you want to give up? This will start a new game.")) {
        restartGame();
      }
    });

    
  
    function restartGame() {
      updateForm(0);
      document.getElementById("levelIndex").value = 0;
      document.getElementById("inputField").value = "";
    }
  
    updateForm(0);
  });
  
  
  
  
  