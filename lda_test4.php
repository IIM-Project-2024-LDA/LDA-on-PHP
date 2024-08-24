<!DOCTYPE html>
<html lang="en">
<head>
  <title>BASIC LATENT DIRICHLET ALLOCATION EXAMPLE</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
    <h1>BASIC INTERACTION WITH LDA</h1>      
    <p>Latent Dirichlet Allocation (LDA) is a topic modeling technique for uncovering the central topics and their distributions across a set of documents</p>
  </div>

  <form method="post">
    <div class="form-group">
      <label for="sentence1">Sentence 1:</label>
      <input type="text" class="form-control" id="sentence1" name="sentence1" required>
    </div>
    <div class="form-group">
      <label for="sentence2">Sentence 2:</label>
      <input type="text" class="form-control" id="sentence2" name="sentence2" required>
    </div>
    <div class="form-group">
      <label for="sentence3">Sentence 3:</label>
      <input type="text" class="form-control" id="sentence3" name="sentence3" required>
    </div>
    <div class="form-group">
      <label for="sentence4">Sentence 4:</label>
      <input type="text" class="form-control" id="sentence4" name="sentence4" required>
    </div>
    <div class="form-group">
      <label for="sentence5">Sentence 5:</label>
      <input type="text" class="form-control" id="sentence5" name="sentence5" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>

<?php

require 'vendor/autoload.php';

use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Clustering\KMeans;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documents = [
        $_POST['sentence1'],
        $_POST['sentence2'],
        $_POST['sentence3'],
        $_POST['sentence4'],
        $_POST['sentence5']
    ];

    

    class LDA
    {
        protected $documents;
        protected $vectorizer;

        public function __construct(array $documents)
        {
            $this->documents = $documents;
            $this->vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new English());
        }

        public function preprocess()
        {
            $this->vectorizer->fit($this->documents);
            $this->vectorizer->transform($this->documents);
            return $this->vectorizer->getVocabulary();
        }

        public function getTransformedDocuments()
        {
            return $this->documents;
        }

        public function cluster($k = 2)
        {
            $samples = $this->getTransformedDocuments();
            $kmeans = new KMeans($k);
            return $kmeans->cluster($samples);
        }
    }

    // Create LDA instance and preprocess documents
    $lda = new LDA($documents);
    $vocabulary = $lda->preprocess();
    $clusters = $lda->cluster(2); // Assuming we want to cluster into 2 topics

    echo "<h2>Example of the sentences we input</h2>";
    for ($i = 0; $i < count($documents); $i++) {
        echo "<p>Sentence " . ($i + 1) . ": " . htmlspecialchars($documents[$i]) . "</p>";
    }

    echo '<table class="table">';
    echo '<thead><tr><th>Vocabulary</th>';
    for ($y = 0; $y <= 4; $y++) {
        echo "<th>Sentence " . ($y + 1) . "</th>";
    }
    echo '</tr></thead><tbody><td></td>';

    for ($lineNumber = 1; $lineNumber <6; $lineNumber++) {

        echo "<td>";
        for ($topicNumber = 0; $topicNumber <10; $topicNumber++) {
          if(@is_integer($clusters[$topicNumber][$lineNumber-1][1])){
            echo "Topic ";
            echo $topicNumber+1;
            break;
          }
          else{
            continue;
          }
        }
        echo "</td>";
      
      }
            echo"</tr>";
      for ($x = 0; $x <= 23; $x++) {
        echo "<tr>";
        echo "<td>";
        echo $vocabulary[$x];
        echo "</td>";
          
      
        
          for ($lineNumber = 1; $lineNumber <6; $lineNumber++) {
            for ($topicNumber = 0; $topicNumber <10; $topicNumber++) {
              if(@is_integer($clusters[$topicNumber][$lineNumber-1][1])){
                echo "<td>";
                echo $clusters[$topicNumber][$lineNumber-1][$x];
                echo "</td>";
                break;
              }
              else{
                continue;
              }
            }
            
          }
        
        echo "</tr>";
      }
    

    echo '</tbody></table>';
}
?>

</div>
</body>
</html>
