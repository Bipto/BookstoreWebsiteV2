<?php

require_once '/var/www/shared/data/book.php';
require_once '/var/www/shared/data/author.php';
require_once '/var/www/shared/data/genre.php';

function insertGenre(Genre $genre, PDO $conn)
{
    try {
        $stmt = $conn->prepare(
            "INSERT INTO bookstore.genres
        (
            name,
            description
        )
        VALUES
        (
            :name,
            :description
        )"
        );

        $stmt->execute([
            'name' => $genre->name,
            'description' => $genre->description
        ]);

        echo 'Inserted genre: ' . $genre->name . '<br>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function insertAuthor(Author $author, PDO $conn)
{
    try {
        $stmt = $conn->prepare(
            "INSERT INTO bookstore.authors
        (
            name,
            description,
            image_path
        )
        VALUES
        (
            :name,
            :description,
            :image_path
        )"
        );

        $stmt->execute([
            'name' => $author->Name,
            'description' => $author->Description,
            'image_path' => $author->ImagePath
        ]);

        echo 'Inserted author: ' . $author->Name . '<br>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function insertBook(Book $book, PDO $conn)
{
    try {
        $stmt = $conn->prepare(
            "INSERT INTO Bookstore.Books
        (
            title,
            author_id,
            book_description,
            price,
            stock_count,
            image_path
        )
        VALUES
        (
            :title,
            :author_id,
            :book_description,
            :price,
            :stock_count,
            :image_path
        )"
        );

        $stmt->execute([
            'title' => $book->Title,
            'author_id' => $book->Author,
            'book_description' => $book->Description,
            'price' => $book->Price,
            'stock_count' => $book->StockCount,
            'image_path' => $book->ImagePath
        ]);

        $bookId = $conn->lastInsertId();

        $genreCount = count($book->Genres);
        if ($genreCount > 0) {
            $stmt = $conn->prepare(
                "INSERT INTO bookstore.book_genres
            (
                book_id,
                genre_id
            )
            VALUES
            (
                :book_id,
                :genre_id
            )"
            );

            foreach ($book->Genres as $genre) {
                $stmt->execute([
                    'book_id' => $bookId,
                    'genre_id' => $genre
                ]);
            }
        }

        echo 'Inserted book: ' . $book->Title . '<br>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function createGenres(PDO $conn)
{
    $genre1 = new Genre(
        "Fantasy",
        "Fantasy is a genre of speculative fiction that involves supernatural or magical elements, often including completely imaginary realms and creatures.

The genre's roots lie in fantasy literature and drama. From the twentieth century onward, it has expanded into various media, including film, television, graphic novels, manga, animation, and video games.

The expression fantastic literature is often used for this genre by Anglophone literary critics. An archaic spelling for the term is phantasy."
    );
    insertGenre($genre1, $conn);

    $genre2 = new Genre(
        "Children's Fantasy",
        "Children's fantasy is a subgenre of fantasy literature intended for young readers. It may also refer to fantasy read by children, regardless of the intended audience.

        The genre has roots in folk tales such as Aesop's Fables that were not originally intended for children: before the Victorian era, fairytales were perceived as immoral and ill-suited for children's minds. A market for children's fantasy was established in Britain in the 19th century, leading to works such as Lewis Carroll's Alice's Adventures in Wonderland and Edith Nesbit's Five Children series; the genre also developed in America, exemplified by L. Frank Baum's The Wonderful Wizard of Oz. Of the authors of this period, Nesbit is commonly cited as the creator of modern children's fantasy.
        
        The golden age of children's fantasy, in scholars' view, occurred in the mid-20th century when the genre was influenced by J. R. R. Tolkien's The Hobbit and C. S. Lewis's The Chronicles of Narnia. In the vein of Narnia, the post-war period saw rising stakes and manifestations of evil in the works of Susan Cooper and Alan Garner. Tolkien's Middle-earth led to mythopoeic fantasy in the 1970s, from authors such as Ursula K. Le Guin and Robin McKinley.[12] Another influential writer of this period was Diana Wynne Jones, who wrote both medievalist and realist fantasies.
        
        In the late 1990s, J. K. Rowling's Harry Potter led to a commercial boom in the genre, reviving older authors' careers and spawning many imitators. A concurrent success is Philip Pullman's His Dark Materials, a darker, realistic fantasy that led to a corresponding trend in a new young adult market."
    );
    insertGenre($genre2, $conn);

    $genre3 = new Genre(
        "Young Adults",
        "Young adult literature (YA) is literature written for readers aged 12 to 18. It may include themes found in adult fiction, such as family dysfunction, substance abuse, alcoholism, and sexuality.

        The earliest known use of term young adult occurred in 1942. Librarians developed the category of young adult literature to help bridge the gap between children's literature and adult literature. According to a study conducted in 2023, 55% of young adult literature consumers were over 18 years of age. 78% of adult consumers purchased with the intent to read themselves. Of these adult buyers, 51% were between ages 30 and 44. This highlights the fact that readers of young adult literature are often adults."
    );
    insertGenre($genre3, $conn);

    $genre4 = new Genre(
        "Dystopian",
        "A dystopia is a society characterized by a focus on that which is contrary to the author's ethos, such as mass poverty, public mistrust and suspicion, a police state or oppression. Most authors of dystopian fiction explore at least one reason why things are that way, often as an analogy for similar issues in the real world. Dystopian literature serves to \"provide fresh perspectives on problematic social and political practices that might otherwise be taken for granted or considered natural and inevitable\". Some dystopias claim to be utopias. Samuel Butler's Erewhon can be seen as a dystopia because of the way sick people are punished as criminals while thieves are \"cured\" in hospitals, which the inhabitants of Erewhon see as natural and right, i.e., utopian (as mocked in Voltaire's Candide).

        Dystopias usually extrapolate elements of contemporary society, and thus can be read as political warnings.
        
        Eschatological literature is a form of literature that can go hand-in-hand with dystopian literature. This is a form of literature that specifically focuses on some form of apocalypse, such as the collapse of a society, the end of an era of human history, or the end of the world itself."
    );
    insertGenre($genre4, $conn);

    $genre5 = new Genre(
        "High Fantasy",
        "High fantasy, or epic fantasy, is a subgenre of fantasy defined by the epic nature of its setting or by the epic stature of its characters, themes, or plot. High fantasy is usually set in an alternative, fictional (\"secondary\") world, rather than the \"real\" or \"primary\" world. This secondary world is usually internally consistent, and often quite developed, but its rules differ from those of the primary world. By contrast, low fantasy is characterized by being set on Earth, the rational primary or \"real world\", upon which the non-rational supernatural or fantasy elements intrude."
    );
    insertGenre($genre5, $conn);

    $genre6 = new Genre(
        "Speculative Fiction",
        "Speculative fiction is an umbrella genre of fiction that encompasses all the subgenres that depart from realism, or strictly imitating everyday reality, instead presenting fantastical, supernatural, futuristic, or other highly imaginative realms or beings.

        This catch-all genre includes but is not limited to fantasy, science fiction, social science fiction, science fantasy, superhero, paranormal and supernatural horror, alternate history, magical realism, slipstream, weird fiction, utopia, dystopia, and apocalyptic and post-apocalyptic fiction. In other words, the genre presents individuals, events, or places beyond the ordinary real world.
        
        The term speculative fiction has been used for works of literature, film, television, drama, video games, radio, and hybrid media."
    );
    insertGenre($genre6, $conn);
}

function createAuthors(PDO $conn)
{
    $author1 = new Author();
    $author1->Name = "J.R.R. Tolkien";
    $author1->Description = "John Ronald Reuel Tolkien 3 January 1892 – 2 September 1973) was an English writer and academic philologist. He was the author of the high fantasy works The Hobbit (1937) and The Lord of the Rings (1954–1955).

    From 1925 to 1945 Tolkien was the Rawlinson and Bosworth Professor of Anglo-Saxon and a Fellow of Pembroke College, both at the University of Oxford. He then moved within the same university to become the Merton Professor of English Language and Literature and Fellow of Merton College, and held these positions from 1945 until his retirement in 1959. Tolkien was a devout Catholic and a close friend of C. S. Lewis, a co-member of the Inklings, an informal literary discussion group. He was appointed a Commander of the Order of the British Empire by Elizabeth II on 28 March 1972.
    
    After Tolkien's death, his son Christopher published a series of works based on his father's extensive notes and unpublished manuscripts, including The Silmarillion. These, together with The Hobbit and The Lord of the Rings, form a connected body of tales, poems, fictional histories, invented languages, and literary essays about a fantasy world called Arda and, within it, Middle-earth. Between 1951 and 1955 Tolkien applied the term legendarium to the larger part of these writings.
    
    While many other authors had published works of fantasy before Tolkien, the tremendous success of The Hobbit and The Lord of the Rings ignited a profound interest in the fantasy genre and ultimately precipitated an avalanche of new fantasy books and authors. This has led to his popular identification as the \"father\" of modern fantasy literature. He is widely regarded as one of the most influential authors of all time.";
    $author1->ImagePath = "J._R._R._Tolkien,_ca._1925.jpg";
    insertAuthor($author1, $conn);

    $author2 = new Author();
    $author2->Name = "J.K. Rowling";
    $author2->Description = "Joanne Rowling (born 31 July 1965), better known by her pen name J. K. Rowling, is a British author, philanthropist, producer, and screenwriter. She is best known for writing Harry Potter, a seven-volume series about a young wizard and the best-selling book series in history, with over 600 million copies sold. She also writes Cormoran Strike, an ongoing crime fiction series, under the alias Robert Galbraith.

    Rowling conceived the idea for the Harry Potter series in 1990. The seven-year period that followed saw the death of her mother, the birth of her first child, divorce from her first husband, and relative poverty until the first novel in the series, Harry Potter and the Philosopher's Stone, was published in 1997. Six sequels followed, concluding with Harry Potter and the Deathly Hallows (2007). In 2008, Forbes named her the world's highest-paid author. The books have been translated into 85 languages and have spawned a global media franchise including films and video games. The series revived fantasy as a genre in the children's market, spawned a host of imitators, and inspired an active fandom. Critical reception has been more mixed. Many reviewers see Rowling's writing as conventional; some regard her portrayal of gender and social division as regressive. There were also religious debates over the Harry Potter series.
    
    Rowling has won many accolades for her work. She was named to the Order of the British Empire and was appointed a member of the Order of the Companions of Honour for services to literature and philanthropy. Harry Potter brought her wealth and recognition, which she has used to advance philanthropic endeavours and political causes. She established the Volant Charitable Trust in 2000, and co-founded the charity Lumos in 2005. Rowling's philanthropy has centred on medical causes and supporting at-risk women and children. Forbes has estimated that Rowling's total charitable giving between 2005 and 2025 exceeded US$250 million. She has also donated to the British Labour Party, and opposed Scottish independence and Brexit.
    
    Rowling has attracted criticism for trans-exclusionary positions she has taken from 2019 onwards, including opposition to gender self-identification and to allowing transgender women into women's toilets and changing rooms. She has supported and donated to the campaign group For Women Scotland, the plaintiffs in For Women Scotland Ltd v The Scottish Ministers.";

    $author2->ImagePath = "J._K._Rowling_2010.jpg.webp";
    insertAuthor($author2, $conn);

    $author3 = new Author();
    $author3->Name = "Suzanne Collins";
    $author3->Description = "Suzanne Collins (born August 10, 1962)[2] is an American author and television writer who is best known as the author of the young adult dystopian fiction book series The Hunger Games. She is also the author of the children's fantasy series The Underland Chronicles.";
    $author3->ImagePath = "Suzanne_Collins_David_Shankbone_2010.jpg";
    insertAuthor($author3, $conn);

    $author4 = new Author();
    $author4->Name = "George R.R. Martin";
    $author4->Description = "George Raymond Richard Martin (born George Raymond Martin, September 20, 1948), also known by the initials GRRM, is an American author, screenwriter, and television producer. Martin is best known as the author of the epic fantasy novel series A Song of Ice and Fire, which was adapted by HBO into the Primetime Emmy Award–winning television series Game of Thrones (2011–2019) and its prequel series House of the Dragon (2022–present). Martin also wrote a related series of novellas, Tales of Dunk and Egg, which have been adapted by HBO as A Knight of the Seven Kingdoms (2026–present). Outside of A Song of Ice and Fire and its related media, Martin helped create the Wild Cards anthology series and contributed worldbuilding for the video game Elden Ring (2022).

    In 2005, Lev Grossman of Time called Martin \"the American Tolkien\", and in 2011, he was included on the annual Time 100 list of the most influential people in the world. He is a longtime resident of Santa Fe, New Mexico, where he helped fund Meow Wolf and owns the Jean Cocteau Cinema.";
    $author4->ImagePath = "George_R._R._Martin_(54743316729)_(cropped).jpg";
    insertAuthor($author4, $conn);

    $author5 = new Author();
    $author5->Name = "George Orwell";
    $author5->Description = "Eric Arthur Blair (25 June 1903 – 21 January 1950) was an English novelist, poet, essayist, journalist, and critic who wrote under the pen name of George Orwell. His work is characterised by lucid prose, social criticism, opposition to all totalitarianism (both authoritarian communism and fascism), and support of democratic socialism.

    Orwell is best known for his allegorical novella Animal Farm (1945) and the dystopian novel Nineteen Eighty-Four (1949), although his works also encompass literary criticism, poetry, fiction and polemical journalism. His non-fiction works, including The Road to Wigan Pier (1937), documenting his experience of working-class life in industrial Northern England, and Homage to Catalonia (1938), an account of his experiences soldiering for the Republican faction of the Spanish Civil War (1936–1939), are as critically respected as his essays on politics, literature, language and culture.
    
    Orwell's work remains influential in both popular and political culture, with the adjective \"Orwellian\"—describing totalitarian and authoritarian social practices—becoming part of the English language, as are many of his neologisms, such as \"Big Brother\", \"Thought Police\", \"Room 101\", \"Newspeak\", \"memory hole\", \"doublethink\", and \"thoughtcrime\". In 2008, The Times named Orwell the second-greatest British writer since 1945.";
    $author5->ImagePath = "George_Orwell_press_photo.jpg";
    insertAuthor($author5, $conn);
}

function createBooks(PDO $conn)
{
    $book1 = new Book();
    $book1->BookID = 0;
    $book1->Title = "The Hobbit";
    $book1->Author = 1;
    $book1->Genres = [2, 5];
    $book1->Price = 7.99;
    $book1->StockCount = 30;
    $book1->ImagePath = "the_hobbit.jpg";
    $book1->Description = "The Hobbit, or There and Back Again is a children's fantasy novel by English author J. R. R. Tolkien. It was published in 1937 to wide critical acclaim, being nominated for the Carnegie Medal and awarded a prize from the New York Herald Tribune for best juvenile fiction. The book remains popular and is recognized as a classic in children's literature.
<br><br>

The Hobbit is set within Tolkien's fictional universe and follows the quest of home-loving Bilbo Baggins, the titular hobbit, to win a share of the treasure guarded by a dragon named Smaug. Bilbo's journey takes him from his light-hearted, rural surroundings into more sinister territory.
<br><br>

The story is told in the form of an episodic quest, and most chapters introduce a specific creature or type of creature of Tolkien's geography. Bilbo gains a new level of maturity, competence, and wisdom by accepting the disreputable, romantic, fey, and adventurous sides of his nature and applying his wits and common sense. The story reaches its climax in the Battle of Five Armies, where many of the characters and creatures from earlier chapters re-emerge to engage in conflict.
<br><br>

Personal growth and forms of heroism are central themes of the story, along with motifs of warfare. These themes have led critics to view Tolkien's own experiences during World War I as instrumental in shaping the story. The author's scholarly knowledge of Germanic philology and interest in mythology and fairy tales are often noted as influences.
<br><br>

The publisher was encouraged by the book's critical and financial success and, therefore, requested a sequel. As Tolkien's work progressed on its successor, The Lord of the Rings, he made retrospective accommodations for it in The Hobbit. These few but significant changes were integrated into the second edition. Further editions followed with minor emendations, including those reflecting Tolkien's changing concept of the world into which Bilbo stumbled.
<br><br>

The work has never been out of print. Its ongoing legacy encompasses many adaptations for stage, screen, radio, board games, and video games. Several of these adaptations have received critical recognition on their own merits. ";
    insertBook($book1, $conn);

    $book2 = new Book();
    $book2->BookID = 1;
    $book2->Title = "The Fellowship of the Ring";
    $book2->Author = 1;
    $book2->Genres = [5];
    $book2->Price = 7.99;
    $book2->ImagePath = "fellowship_of_the_ring.png";
    $book2->StockCount = 30;
    $book2->Description = "The Fellowship of the Ring is the first of three volumes of the epic novel The Lord of the Rings by the English author J. R. R. Tolkien. It is followed by The Two Towers and The Return of the King. It takes place in the fictional universe of Middle-earth, and was originally published on 29 July 1954 in the United Kingdom.
<br><br>
The volume consists of a foreword, in which the author discusses his writing of The Lord of the Rings, a prologue titled \"Concerning Hobbits, and other matters\", and the main narrative in Book I and Book II.";
    insertBook($book2, $conn);

    $book3 = new Book();
    $book3->BookID = 2;
    $book3->Title = "The Two Towers";
    $book3->Author = 1;
    $book3->Genres = [5];
    $book3->Price = 7.99;
    $book3->ImagePath = "the_two_towers.png";
    $book3->StockCount = 30;
    $book3->Description = "The Two Towers is the second volume of J. R. R. Tolkien's high fantasy novel The Lord of the Rings. It is preceded by The Fellowship of the Ring and followed by The Return of the King.";
    insertBook($book3, $conn);

    $book4 = new Book();
    $book4->BookID = 3;
    $book4->Title = "The Return of the King";
    $book4->Author = 1;
    $book4->Genres = [5];
    $book4->Price = 7.99;
    $book4->ImagePath = "the_return_of_the_king.png";
    $book4->StockCount = 30;
    $book4->Description = "The Return of the King is the third and final volume of J. R. R. Tolkien's The Lord of the Rings, following The Fellowship of the Ring and The Two Towers. It was published in 1955. The story begins in the kingdom of Gondor, which is soon to be attacked by the Dark Lord Sauron.";
    insertBook($book4, $conn);

    $book5 = new Book();
    $book5->BookID = 4;
    $book5->Title = "The Silmarillion";
    $book5->Author = 1;
    $book5->Genres = [5];
    $book5->Price = 7.99;
    $book5->ImagePath = "the_silmarillion.png";
    $book5->StockCount = 30;
    $book5->Description = "The Silmarillion (Quenya: [silmaˈrilliɔn]) is a collection of myths and stories in varying styles by the English writer J. R. R. Tolkien. It was edited and published posthumously by his son Christopher Tolkien in 1977, assisted by the fantasy author Guy Gavriel Kay. It tells of Eä, a fictional universe that includes the Blessed Realm of Valinor, the once-great region of Beleriand, the sunken island of Númenor, and the continent of Middle-earth, where Tolkien's most popular works—The Hobbit and The Lord of the Rings—are set. After the success of The Hobbit, Tolkien's publisher Stanley Unwin requested a sequel, and Tolkien offered a draft of the writings that would later become The Silmarillion. Unwin rejected this proposal, calling the draft obscure and \"too Celtic\", so Tolkien began working on a new story that eventually became The Lord of the Rings.
<br><br>

The Silmarillion has five parts. The first, Ainulindalë, tells in mythic style of the creation of Eä, the \"world that is.\" The second part, Valaquenta, gives a description of the Valar and Maiar, supernatural powers of Eä. The next section, Quenta Silmarillion, which forms the bulk of the collection, chronicles the history of the events before and during the First Age, including the wars over three jewels, the Silmarils, that gave the book its title. The fourth part, Akallabêth, relates the history of the Downfall of Númenor and its people, which takes place in the Second Age. The final part, Of the Rings of Power and the Third Age, is a brief summary of the events of The Lord of the Rings and those that led to them.
<br><br>

The book shows the influence of many sources, including the Finnish epic Kalevala, Greek mythology in the lost island of Atlantis (as Númenor) and the Olympian gods (in the shape of the Valar, though these also resemble the Norse Æsir).
<br><br>

Because J. R. R. Tolkien died leaving his legendarium unedited, Christopher Tolkien selected and edited materials to tell the story from start to end. In a few cases, this meant that he had to devise completely new material, within the tenor of his father's thought, to resolve gaps and inconsistencies in the narrative, particularly Chapter 22, \"Of the Ruin of Doriath\".
<br><br>

The Silmarillion received a generally poor reception on publication; it sold much less well than The Lord of the Rings. Scholars found the work problematic, not least because the book is a construction, not authorised by Tolkien himself, from the large corpus of documents and drafts also called \"The Silmarillion\". Scholars have noted that Tolkien intended the work to be a mythology, penned by many hands, and redacted by a fictional editor, whether Ælfwine or Bilbo Baggins. As such, the scholar Gergely Nagy considers that the fact that the work has indeed been edited actually realises Tolkien's intention.";
    insertBook($book5, $conn);

    $book6 = new Book();
    $book6->BookID = 5;
    $book6->Title = "Unfinished Tales of Numenor and Middle-Earth";
    $book6->Author = 1;
    $book6->Genres = [5];
    $book6->Price = 7.99;
    $book6->ImagePath = "unfinished_tales_of_numenor_middle_earth.jpg";
    $book6->StockCount = 30;
    $book6->Description = "Unfinished Tales of Númenor and Middle-earth is a collection of stories and essays by J. R. R. Tolkien that were never completed during his lifetime, but were edited by his son Christopher Tolkien and published in 1980. Many of the tales within are retold in The Silmarillion, albeit in modified forms; the work also contains a summary of the events of The Lord of the Rings told from a less personal perspective.";
    insertBook($book6, $conn);

    $book7 = new Book();
    $book7->BookID = 6;
    $book7->Title = "The Fall of Numenor";
    $book7->Author = 1;
    $book7->Genres = [5];
    $book7->Price = 7.99;
    $book7->ImagePath = "fall_of_numenor.jpg";
    $book7->StockCount = 30;
    $book7->Description = "The Fall of Númenor is a fantasy work collecting all J. R. R. Tolkien's Second Age writings together. It is edited by Brian Sibley. The book uses \"The Tale of Years\" in the Appendices of The Lord of the Rings to present the content of the Second Age. The stories include the foundation of Númenor, the forging of the Rings of Power, and the Last Alliance against Sauron that ended the Second Age. The editor, Brian Sibley, has provided new introductions and commentaries to unify Tolkien's writings. The publication contains ten new colour paintings by Alan Lee. The book published on 15 November 2022.";
    insertBook($book7, $conn);

    $book8 = new Book();
    $book8->BookID = 7;
    $book8->Title = "Harry Potter and the Philosopher's Stone";
    $book8->Author = 2;
    $book8->Genres = [1];
    $book8->Price = 7.99;
    $book8->ImagePath = "harry_potter_philosophers_stone.png";
    $book8->StockCount = 30;
    $book8->Description = "Harry Potter and the Philosopher's Stone is a fantasy novel written by British author J. K. Rowling. The first novel in the Harry Potter series and Rowling's debut novel, it follows Harry Potter, a young wizard who discovers his magical heritage on his eleventh birthday, when he receives a letter of acceptance to Hogwarts School of Witchcraft and Wizardry. Harry makes close friends and a few enemies during his first year at the school and with the help of his friends, Ron Weasley and Hermione Granger, he faces an attempted comeback by the dark wizard Lord Voldemort, who killed Harry's parents, but failed to kill Harry when he was just 15 months old.
<br><br>

The book was first published in the United Kingdom on 26 June 1997 by Bloomsbury. It was published in the United States the following year by Scholastic Corporation under the title Harry Potter and the Sorcerer's Stone. It won most of the British book awards that were judged by children and other awards in the US. The book reached the top of the New York Times list of best-selling fiction in August 1999 and stayed near the top of that list for much of 1999 and 2000. It has been translated into at least 73 other languages and has been made into a feature-length film of the same name, as have all six of its sequels. The novel has sold in excess of 120 million copies, making it the third best-selling book of all time.
<br><br>

Most reviews were very favourable, commenting on Rowling's imagination, humour, simple, direct style and clever plot construction, although a few complained that the final chapters seemed rushed. The writing has been compared to that of Jane Austen, one of Rowling's favourite authors; Roald Dahl, whose works dominated children's stories before the appearance of Harry Potter; and the ancient Greek story-teller Homer. While some commentators thought the book looked backwards to Victorian and Edwardian boarding school stories, others thought it placed the genre firmly in the modern world by featuring contemporary ethical and social issues, as well as overcoming obstacles like bullies.
<br><br>

The Harry Potter series has been used as a source of object lessons in educational techniques, sociological analysis and marketing.";
    insertBook($book8, $conn);

    $book9 = new Book();
    $book9->BookID = 8;
    $book9->Title = "Harry Potter and the Chamber of Secrets";
    $book9->Author = 2;
    $book9->Genres = [1];
    $book9->Price = 7.99;
    $book9->ImagePath = "harry_potter_chamber_of_secrets.png";
    $book9->StockCount = 30;
    $book9->Description = "Harry Potter and the Chamber of Secrets is a fantasy novel written by British author J. K. Rowling and the second novel in the Harry Potter series. The plot follows Harry's second year at Hogwarts School of Witchcraft and Wizardry, during which a series of messages on the walls of the school's corridors warn that the \"Chamber of Secrets\" has been opened and that the \"heir of Slytherin\" would kill all pupils who do not come from all-magical families. These threats are found after attacks that leave residents of the school petrified. Throughout the year, Harry and his friends Ron and Hermione investigate the attacks.
<br><br>

The book was published in the United Kingdom on 2 July 1998 by Bloomsbury and later in the United States on 2 June 1999 by Scholastic Inc. Although Rowling says she found it difficult to finish the book, it won high praise and awards from critics, young readers, and the book industry, although some critics thought the story was perhaps too frightening for younger children. Much like with other novels in the series, Harry Potter and the Chamber of Secrets triggered religious debates; some religious authorities have condemned its use of magical themes, whereas others have praised its emphasis on self-sacrifice and the way one's character is the result of one's choices.
<br><br>

Several commentators have noted that personal identity is a strong theme in the book and that it addresses issues of racism through the treatment of non-human, non-magical, and non-living people. Some commentators regard the story's diary that writes back as a warning against uncritical acceptance of information from sources whose motives and reliability cannot be checked. Institutional authority is portrayed as self-serving and incompetent.
<br><br>

The film adaptation of the novel, released in 2002, became (at the time) the sixth highest-grossing film ever and received generally favourable reviews. Video games loosely based on Harry Potter and the Chamber of Secrets were also released for several platforms, and most obtained favourable reviews.";
    insertBook($book9, $conn);

    $book10 = new Book();
    $book10->BookID = 9;
    $book10->Title = "Harry Potter and the Prisoner of Azkaban";
    $book10->Author = 2;
    $book10->Genres = [1];
    $book10->Price = 7.99;
    $book10->ImagePath = "harry_potter_prisoner_of_azkaban.png";
    $book10->StockCount = 30;
    $book10->Description = "Harry Potter and the Prisoner of Azkaban is a fantasy novel written by British author J. K. Rowling and is the third in the Harry Potter series. The book follows Harry Potter, a young wizard, in his third year at Hogwarts School of Witchcraft and Wizardry. Along with friends Ronald Weasley and Hermione Granger, Harry investigates Sirius Black, an escaped prisoner from Azkaban, the wizard prison, believed to be one of Lord Voldemort's old allies.
<br><br>

The book was published in the United Kingdom on 8 July 1999 by Bloomsbury and in the United States on 8 September 1999 by Scholastic, Inc. Rowling found the book easy to write, finishing it just a year after she began writing it. The book sold 68,000 copies in just three days after its release in the United Kingdom and since has sold over three million in the country. The book won the 1999 Whitbread Children's Book Award, the Bram Stoker Award, and the 2000 Locus Award for Best Fantasy Novel and was short-listed for other awards, including the Hugo.
<br><br>

The film adaptation of the novel was released in 2004, grossing more than $796 million and earning critical acclaim. Video games loosely based on Harry Potter and the Prisoner of Azkaban were also released for several platforms, and most obtained favourable reviews.";
    insertBook($book10, $conn);

    $book11 = new Book();
    $book11->BookID = 10;
    $book11->Title = "Harry Potter and the Goblet of Fire";
    $book11->Author = 2;
    $book11->Genres = [1];
    $book11->Price = 7.99;
    $book11->ImagePath = "harry_potter_goblet_of_fire.png";
    $book11->StockCount = 30;
    $book11->Description = "Harry Potter and the Goblet of Fire is a fantasy novel written by British author J. K. Rowling and the fourth novel in the Harry Potter series. It follows Harry Potter, a wizard in his fourth year at Hogwarts School of Witchcraft and Wizardry, and the mystery surrounding the entry of Harry's name into the Triwizard Tournament, in which he is forced to compete.
<br><br>

The book was published in the United Kingdom by Bloomsbury and in the United States by Scholastic. In both countries, the release date was 8 July 2000. This was the first time a book in the series was published in both countries at the same time. The novel won a Hugo Award, the only Harry Potter novel to do so, in 2001. The book was adapted into a film, released worldwide on 18 November 2005, and a video game by Electronic Arts.";
    insertBook($book11, $conn);

    $book12 = new Book();
    $book12->BookID = 11;
    $book12->Title = "Harry Potter and the Order of the Phoenix";
    $book12->Author = 2;
    $book12->Genres = [1];
    $book12->Price = 7.99;
    $book12->ImagePath = "harry_potter_order_of_the_phoenix.png";
    $book12->StockCount = 30;
    $book12->Description = "Harry Potter and the Order of the Phoenix is a fantasy novel written by British author J. K. Rowling and the fifth novel in the Harry Potter series. It follows Harry Potter's struggles through his fifth year at Hogwarts School of Witchcraft and Wizardry, including the surreptitious return of the antagonist Lord Voldemort, O.W.L. exams, and an obstructive Ministry of Magic. The novel was published on 21 June 2003 by Bloomsbury in the United Kingdom, Scholastic in the United States, and Raincoast in Canada. It sold five million copies in the first 24 hours of publication.
<br><br>

Harry Potter and the Order of the Phoenix won several awards, including the American Library Association Best Book Award for Young Adults in 2003. The book was also made into a 2007 film, and a video game by Electronic Arts.";
    insertBook($book12, $conn);

    $book13 = new Book();
    $book13->BookID = 12;
    $book13->Title = "Harry Potter and the Half Blood Prince";
    $book13->Author = 2;
    $book13->Genres = [1];
    $book13->Price = 7.99;
    $book13->ImagePath = "harry_potter_half_blood_prince.png";
    $book13->StockCount = 30;
    $book13->Description = "Harry Potter and the Half-Blood Prince is a fantasy novel written by British author J. K. Rowling and the sixth and penultimate novel in the Harry Potter series. Set during Harry Potter's sixth year at Hogwarts, the novel explores the past of the boy wizard's nemesis, Lord Voldemort, and Harry's preparations for the final battle against Voldemort alongside his headmaster and mentor Albus Dumbledore.
<br><br>

The book was published in the United Kingdom by Bloomsbury and in the United States by Scholastic on 16 July 2005, as well as in several other countries. It sold almost seven million copies in the first 24 hours after its release, a record eventually broken by its sequel, Harry Potter and the Deathly Hallows. There were many controversies before and after it was published, including the right-to-read copies delivered before the release date in Canada. Reception to the novel was generally positive, and it won several awards and honours, including the 2006 British Book of the Year award.
<br><br>

Reviewers noted that the book had a darker tone than its predecessors, though it did contain some humour. Some considered the main themes love, death, trust, and redemption. The considerable character development of Harry and many other teenage characters also drew attention.";
    insertBook($book13, $conn);

    $book14 = new Book();
    $book14->BookID = 13;
    $book14->Title = "Harry Potter and the Deathly Hallows";
    $book14->Author = 2;
    $book14->Genres = [1];
    $book14->Price = 7.99;
    $book14->ImagePath = "harry_potter_and_the_deathly_hallows.jpg";
    $book14->StockCount = 30;
    $book14->Description = "Harry Potter and the Deathly Hallows is a fantasy novel written by British author J. K. Rowling and the seventh and final novel of the main Harry Potter series. It was released on 21 July 2007 in the United Kingdom by Bloomsbury Publishing, in the United States by Scholastic, and in Canada by Raincoast Books. The novel chronicles the events directly following Harry Potter and the Half-Blood Prince (2005) and the final confrontation between the wizards Harry Potter and Lord Voldemort.
<br><br>

Deathly Hallows shattered sales records upon release, surpassing marks set by previous titles of the Harry Potter series. It holds the Guinness World Record for most novels sold within 24 hours of release, with 8.3 million sold in the US and 2.65 million in the UK. Reception to the book was generally positive, and the book won the 2008 Colorado Blue Spruce Book Award, and the American Library Association named it the \"Best Book for Young Adults\". A play, Harry Potter and the Cursed Child, was written by Jack Thorne, and based on an original story written by J. K. Rowling, John Tiffany, and Thorne, premiering at the Palace Theatre in London on 30 July 2016. Rowling has referred to the play as \"the eighth Harry Potter story\", and is set nineteen years after the events of the Deathly Hallows, effectively picking up where the epilogue of the seventh book ended.

A film adaptation of the novel was released in two parts: Harry Potter and the Deathly Hallows – Part 1 in November 2010 and Part 2 in July 2011.";
    insertBook($book14, $conn);

    $book15 = new Book();
    $book15->BookID = 14;
    $book15->Title = "The Hunger Games";
    $book15->Author = 3;
    $book15->Genres = [3, 4];
    $book15->Price = 7.99;
    $book15->ImagePath = "the_hunger_games.jpg";
    $book15->StockCount = 30;
    $book15->Description = "The Hunger Games is a series of young adult dystopian novels written by American author Suzanne Collins. The first three novels are part of a trilogy following teenage protagonist Katniss Everdeen, and the fourth book is a prequel set 64 years before the original.
<br><br>

The novels in the trilogy are titled The Hunger Games (2008), Catching Fire (2009), and Mockingjay (2010). Each was adapted for film, establishing The Hunger Games film series, with the film adaptation of Mockingjay split into two feature-length motion pictures. The first two books in the series were both New York Times best sellers, and Mockingjay topped all US bestseller lists upon its release. By the time the film adaptation of The Hunger Games was released in 2012, the publisher had reported over 26 million Hunger Games trilogy books in print, including movie tie-in books.
<br><br>

The Hunger Games universe is a dystopia set in Panem, a North American country consisting of the wealthy Capitol and 13 districts in varying states of poverty. Every year, children from the first 12 districts are selected via lottery to participate in a compulsory televised battle royale death match called The Hunger Games.
<br><br>

The 13th district was also subjected to this, but they led a rebellion against the Capitol and brought them to surrender. Since District 13 specialized in nuclear weaponry, the Capitol quickly surrendered and agreed to a peace deal. They both agreed that the residents of District 13 would all move underground and the land above would be bombed to make it seem like the Capitol had won.
<br><br>

The novels were all well received. In August 2012, the series ranked second, exceeded only by the Harry Potter series in NPR’s poll of the top 100 teen novels, which asked voters to choose their favorite young adult books. On August 17, 2012, Amazon announced the Hunger Games trilogy as its top seller, surpassing the record previously held by the Harry Potter series. As of 2014, the trilogy has sold more than 65 million copies in the U.S. alone (more than 28 million copies of The Hunger Games, more than 19 million copies of Catching Fire, and more than 18 million copies of Mockingjay). The Hunger Games trilogy has been sold in 56 territories in 51 languages to date.
<br><br>

A prequel novel, titled The Ballad of Songbirds and Snakes, about the early days of the Hunger Games, featuring a young Coriolanus Snow as the protagonist, was released on May 19, 2020.";
    insertBook($book15, $conn);

    $book16 = new Book();
    $book16->BookID = 15;
    $book16->Title = "The Hunger Games: Catching Fire";
    $book16->Author = 3;
    $book16->Genres = [3, 4];
    $book16->Price = 7.99;
    $book16->ImagePath = "the_hunger_games_catching_fire.jpg";
    $book16->StockCount = 30;
    $book16->Description = "Catching Fire is a 2009 dystopian fiction young adult novel by the American novelist Suzanne Collins, the second book in The Hunger Games series. As the sequel to the 2008 bestseller The Hunger Games, it continues the story of Katniss Everdeen and the post-apocalyptic nation of Panem. Following the events of the previous novel, a rebellion against the oppressive Capitol has begun, and Katniss and fellow tribute Peeta Mellark are forced to return to the arena in a special edition of the Hunger Games.
<br><br>

The book was first published on September 1, 2009, by Scholastic, in hardcover, and was later released in ebook and audiobook format. Catching Fire received mostly positive reviews, with reviewers praising Collins' prose, the book's ending, and the development of Katniss's character. According to critics, major themes of the novel include survival, authoritarianism, rebellion and interdependence versus independence. The book has sold more than 19 million copies in the U.S. alone.
<br><br>

A film adaptation, The Hunger Games: Catching Fire, was released on November 22, 2013.";
    insertBook($book16, $conn);

    $book17 = new Book();
    $book17->BookID = 16;
    $book17->Title = "The Hunger Games: Mockingjay";
    $book17->Author = 3;
    $book17->Genres = [3, 4];
    $book17->Price = 7.99;
    $book17->ImagePath = "the_hunger_games_mockingjay.jpg";
    $book17->StockCount = 30;
    $book17->Description = "Mockingjay is a 2010 science fiction novel by American author Suzanne Collins. It is chronologically the last installment of The Hunger Games series, following 2008's The Hunger Games and 2009's Catching Fire. The book continues the story of Katniss Everdeen, who agrees to unify the districts of Panem in a rebellion against the tyrannical Capitol.
<br><br>

The hardcover and audiobook editions of Mockingjay were published by Scholastic on August 24, 2010, six days after the ebook edition went on sale. The book sold 450,000 copies in the first week of release, exceeding the publisher's expectations. It received a generally positive reaction from critics. The book has been adapted into a two-part movie, with the first part released on November 21, 2014, and the second part released on November 20, 2015.";
    insertBook($book17, $conn);

    $book18 = new Book();
    $book18->BookID = 17;
    $book18->Title = "The Ballad of Songbirds and Snakes";
    $book18->Author = 3;
    $book18->Genres = [3, 4];
    $book18->Price = 7.99;
    $book18->ImagePath = "ballad_of_songbirds_and_snakes.jpg";
    $book18->StockCount = 30;
    $book18->Description = "The Ballad of Songbirds and Snakes is a dystopian action-adventure novel by American author Suzanne Collins. It is a spin-off and a prequel to The Hunger Games trilogy. It was released on May 19, 2020, by Scholastic. An audiobook of the novel read by American actor Santino Fontana was released simultaneously with the printed edition. The book received a virtual launch due to the COVID-19 pandemic. A film adaptation from Lionsgate is set to be released on November 17, 2023.";
    insertBook($book18, $conn);

    $book19 = new Book();
    $book19->BookID = 18;
    $book19->Title = "A Game of Thrones";
    $book19->Author = 4;
    $book19->Genres = [5];
    $book19->Price = 12.50;
    $book19->ImagePath = "a_game_of_thrones.jpg";
    $book19->StockCount = 30;
    $book19->Description = "The first volume in the A Song of Ice and Fire series – unquestionably one of the greatest fantasy epics of all time. Here, we are introduced to the Seven Kingdoms of Westeros, and the major players with eyes on its Iron Throne. As noble families battle for power, greater threats face them all: for winter is coming to Westeros, and here, winter can last a lifetime.";
    insertBook($book19, $conn);

    $book20 = new Book();
    $book20->BookID = 19;
    $book20->Title = "A Clash of Kings";
    $book20->Author = 4;
    $book20->Genres = [5];
    $book20->Price = 12.50;
    $book20->ImagePath = "a_clash_of_kings.jpg";
    $book20->StockCount = 30;
    $book20->Description = "From the ancient citadel of Dragonstone to the forbidding lands of Winterfell, chaos reigns as pretenders to the Iron Throne of the Seven Kingdoms stake their claims through tempest, turmoil and war.
<br><br>

As a prophecy of doom cuts across the sky - a comet the colour of blood and flame - five factions struggle for control of a divided land. Brother plots against brother and the dead rise to walk in the night.";
    insertBook($book20, $conn);

    $book21 = new Book();
    $book21->BookID = 20;
    $book21->Title = "A Storm of Swords: Part 1 Steel and Snow";
    $book21->Author = 4;
    $book21->Genres = [5];
    $book21->Price = 12.50;
    $book21->ImagePath = "a_storm_of_swords_part_1.jpg";
    $book21->StockCount = 30;
    $book21->Description = "Winter approaches Westeros like an angry beast.
<br><br>

The Seven Kingdoms are divided by revolt and blood feud. In the northern wastes, a horde of hungry, savage people steeped in the dark magic of the wilderness is poised to invade the Kingdom of the North where Robb Stark wears his new-forged crown. And Robb's defences are ranged against the South, the land of the cunning and cruel Lannisters, who have his younger sisters in their power.
<br><br>

Throughout Westeros, the war for the Iron Throne rages more fiercely than ever, but if the Wall is breached, no king will live to claim it.";
    insertBook($book21, $conn);

    $book22 = new Book();
    $book22->BookID = 21;
    $book22->Title = "A Storm of Swords: Part 2 Blood and Gold";
    $book22->Author = 4;
    $book22->Genres = [5];
    $book22->Price = 12.50;
    $book22->ImagePath = "a_storm_of_swords_part_2.jpg";
    $book22->StockCount = 30;
    $book22->Description = "The Starks are scattered.
<br><br>

Robb Stark may be King in the North, but he must bend to the will of the old tyrant Walder Frey if he is to hold his crown. And while his youngest sister, Arya, has escaped the clutches of the depraved Cersei Lannister and her son, the capricious boy-king Joffrey, Sansa Stark remains their captive.
<br><br>

Meanwhile, across the ocean, Daenerys Stormborn, the last heir of the Dragon King, delivers death to the slave-trading cities of Astapor and Yunkai as she approaches Westeros with vengeance in her heart.";
    insertBook($book22, $conn);

    $book23 = new Book();
    $book23->BookID = 22;
    $book23->Title = "A Feast for Crows";
    $book23->Author = 4;
    $book23->Genres = [5];
    $book23->Price = 12.50;
    $book23->ImagePath = "a_feast_for_crows.jpg";
    $book23->StockCount = 30;
    $book23->Description = "The Lannisters are in power on the Iron Throne.
<br><br>

The war in the Seven Kingdoms has burned itself out, but in its bitter aftermath new conflicts spark to life. The Martells of Dorne and the Starks of Winterfell seek vengeance for their dead. Euron Crow's Eye, as black a pirate as ever raised a sail, returns from the smoking ruins of Valyria to claim the Iron Isles.
<br><br>

From the icy north, where Others threaten the Wall, apprentice Maester Samwell Tarly brings a mysterious babe in arms to the Citadel. As plots, intrigue and battle threaten to engulf Westeros, victory will go to the men and women possessed of the coldest steel and the coldest hearts.";
    insertBook($book23, $conn);

    $book24 = new Book();
    $book24->BookID = 23;
    $book24->Title = "A Dance With Dragons: Part 1 Dreams and Dust";
    $book24->Author = 4;
    $book24->Genres = [5];
    $book24->Price = 12.50;
    $book24->ImagePath = "a_dance_with_dragons_part_1.jpg";
    $book24->StockCount = 30;
    $book24->Description = "In the aftermath of a colossal battle, new threats are emerging from every direction.
<br><br>

Tyrion Lannister, having killed his father, and wrongfully accused of killing his nephew, King Joffrey, has escaped from King's Landing with a price on his head.
<br><br>

To the north lies the great Wall of ice and stone - a structure only as strong as those guarding it. Eddard Stark's son Jon Snow has been elected 998th Lord Commander of the Night's Watch. But Jon has enemies both inside and beyond the Wall. And in the east Daenerys Targaryen struggles to hold a city built on dreams and dust.";
    insertBook($book24, $conn);

    $book25 = new Book();
    $book25->BookID = 24;
    $book25->Title = "A Dance With Dragons: Part 2 After the Feast";
    $book25->Author = 4;
    $book25->Genres = [5];
    $book25->Price = 12.50;
    $book25->ImagePath = "a_dance_with_dragons_part_2.jpg";
    $book25->StockCount = 30;
    $book25->Description = "The future of the Seven Kingdoms hangs in the balance.
<br><br>

In King's Landing the Queen Regent, Cersei Lannister, awaits trial, abandoned by all those she trusted; while in the eastern city of Yunkai her brother Tyrion has been sold as a slave. From the Wall, having left his wife and the Red Priestess Melisandre under the protection of Jon Snow, Stannis Baratheon marches south to confront the Boltons at Winterfell. But beyond the Wall the wildling armies are massing for an assault...
<br><br>

On all sides bitter conflicts are reigniting, played out by a grand cast of outlaws and priests, soldiers and skinchangers, nobles and slaves. The tides of destiny will inevitably lead to the greatest dance of all.";
    insertBook($book25, $conn);

    $book26 = new Book();
    $book26->BookID = 24;
    $book26->Title = "Nineteen Eighty-Four";
    $book26->Author = 5;
    $book26->Genres = [4, 6];
    $book26->Price = 7.99;
    $book26->ImagePath = "1984.jpg";
    $book26->StockCount = 30;
    $book26->Description = "Nineteen Eighty-Four (also published as 1984) is a dystopian social science fiction novel and cautionary tale by English writer George Orwell. It was published on 8 June 1949 by Secker & Warburg as Orwell's ninth and final book completed in his lifetime. Thematically, it centres on the consequences of totalitarianism, mass surveillance and repressive regimentation of people and behaviours within society. Orwell, a democratic socialist, modelled the authoritarian state in the novel on Stalinist Russia and Nazi Germany. More broadly, the novel examines the role of truth and facts within societies and the ways in which they can be manipulated.
<br><br>

The story takes place in an imagined future in the year 1984, when much of the world is in perpetual war. Great Britain, now known as Airstrip One, has become a province of the totalitarian superstate Oceania, which is led by Big Brother, a dictatorial leader supported by an intense cult of personality manufactured by the Party's Thought Police. Through the Ministry of Truth, the Party engages in omnipresent government surveillance, historical negationism, and constant propaganda to persecute individuality and independent thinking.
<br><br>

The protagonist, Winston Smith, is a diligent mid-level worker at the Ministry of Truth who secretly hates the Party and dreams of rebellion. He keeps a forbidden diary and begins a relationship with his colleague Julia, and they learn about a shadowy resistance group called the Brotherhood. However, their contact with the Brotherhood turns out to be a Party agent, and Smith is arrested. He is subjected to months of psychological manipulation and torture by the Ministry of Love and is released once he has come to love Big Brother.
<br><br>

Nineteen Eighty-Four has become a classic literary example of political and dystopian fiction. It also popularised the term \"Orwellian\" as an adjective, with many terms used in the novel entering common usage, including \"Big Brother\", \"doublethink\", \"Thought Police\", \"thoughtcrime\", \"Newspeak\", and \"2 + 2 = 5\". Parallels have been drawn between the novel's subject matter and real life instances of totalitarianism, mass surveillance, and violations of freedom of expression among other themes. Orwell described his book as a \"satire,\" and a display of the \"perversions to which a centralised economy is liable,\" while also stating he believed \"that something resembling it could arrive.\" Time included the novel on its list of the 100 best English-language novels from 1923 to 2005, and it was placed on the Modern Library's 100 Best Novels list, reaching number 13 on the editors' list and number 6 on the readers' list. In 2003, it was listed at number eight on The Big Read survey by the BBC.";
    insertBook($book26, $conn);
}

$host = $_ENV['POSTGRES_HOST'];
$port = $_ENV['POSTGRES_PORT'];
$dbname = $_ENV['POSTGRES_DB'];
$user = $_ENV['POSTGRES_USER'];
$password = $_ENV['POSTGRES_PASSWORD'];

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "Connected successfully!<br>";

    $pdo->exec("
        DROP SCHEMA bookstore CASCADE
    ");

    $pdo->exec("
        CREATE SCHEMA IF NOT EXISTS bookstore
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.genres(
            genre_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            name VARCHAR(150) NOT NULL UNIQUE,
            description VARCHAR NOT NULL
        )
    ");
    echo 'Created genres table<br>';

    $pdo->exec("
    CREATE TABLE IF NOT EXISTS bookstore.authors(
        author_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
        name VARCHAR(150) NOT NULL UNIQUE,
        description VARCHAR NOT NULL,
        image_path VARCHAR(150)
    )
");
    echo 'Created authors table<br>';

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.books (
            book_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            title VARCHAR(50) NOT NULL UNIQUE,
            author_id INTEGER NOT NULL,
            book_description TEXT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            stock_count INTEGER NOT NULL,
            image_path VARCHAR(150),
            FOREIGN KEY (author_id) REFERENCES bookstore.authors(author_id)
        )
    ");
    echo 'Created books table<br>';

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.book_genres(
            book_id INTEGER,
            genre_id INTEGER,
            PRIMARY KEY (book_id, genre_id),
            FOREIGN KEY (book_id) REFERENCES bookstore.books(book_id),
            FOREIGN KEY (genre_id) REFERENCES bookstore.genres(genre_id)
        )
    ");
    echo 'Created book_genres table<br>';

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.users (
            user_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            email VARCHAR(150) NOT NULL UNIQUE,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            password VARCHAR(150) NOT NULL
        )
    ");
    echo 'Created users table<br>';

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.carts (
            cart_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            user_id INTEGER UNIQUE,
            FOREIGN KEY (user_id) REFERENCES bookstore.users(user_id)
        )
    ");
    echo 'Created carts table<br>';

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookstore.cart_items (
            cart_item_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            cart_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            FOREIGN KEY (cart_id) REFERENCES bookstore.carts(cart_id),
            FOREIGN KEY (book_id) REFERENCES bookstore.books(book_id)

        )
    ");
    echo 'Created cart_items table<br>';

    createGenres($pdo);
    createAuthors($pdo);
    createBooks($pdo);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
