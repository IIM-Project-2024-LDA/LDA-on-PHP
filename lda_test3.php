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
    <p>
<?php
require 'vendor/autoload.php';

use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Clustering\KMeans;

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

// Example documents
$documents = [
    'Machine learning is fascinating.',
    'PHP is a popular scripting language.',
    'Latent Dirichlet Allocation is a topic modeling technique.',
    'Scripting languages like PHP are widely used.',
    'Topic modeling helps in discovering hidden patterns in text.',
];

// Create LDA instance and preprocess documents
$lda = new LDA($documents);
$vocabulary = $lda->preprocess();
$clusters = $lda->cluster(2); // Assuming we want to cluster into 2 topics

 ?>


<h2>Example of the sentences we input</h2>
  <p>Sentence 1: Machine learning is fascinating.</p> 
  <p>Sentence 2: PHP is a popular scripting language.</p> 
  <p>Sentence 3: Latent Dirichlet Allocation is a topic modeling technique.</p> 
  <p>Sentence 4: Scripting languages like PHP are widely used.</p> 
  <p>Sentence 5: Topic modeling helps in discovering hidden patterns in text.</p>            
  <table class="table">
    <thead>
      <tr>
        <th>Vocabulary</th>
<?php
  $name = array ("Sentence 1","Sentence 2","Sentence 3","Sentence 4","Sentence 5");
  for ($y = 0; $y <= 4; $y++) {
	echo "<th>";
  	echo "$name[$y]";
  	echo "</th>";
  }
?>
      </tr>
      
    </thead>
    <tbody>

    <tr>
      <td></td>
<?php

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

?>
    </tbody>
  </table>

<?php
// print_r($vocabulary[0]);



// echo "Vocabulary:\n";
// print_r($vocabulary);



// echo "Clusters:\n";
// print_r($clusters);
// ?>

<!-- </p>   -->
<!-- </div>
  <p>This is some text.</p>      
  <p>This is another text.</p>      
</div> -->

</body>
</html>


