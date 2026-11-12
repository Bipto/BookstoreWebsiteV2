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

    $genre7 = new Genre(
        "Prose Fiction",
        "Prose is language that is natural or normal in structure: following the spontaneous flow of conversational speech, without any perfectly regular rhythm; containing ordinary grammar patterns; or, in writing, aligning to typical conventions and formatting. It thus can range in its exact medium from informal everyday speaking to formal academic writing. Prose differs most notably from poetry, which follows some type of contrived intentional rhythm or other artistically crafted structure or pattern. Poetic structures vary dramatically by language; in English poetry, for instance, language is often organized by a rhythmic metre or rhyme scheme.

        The day-to-day informal language of a region or community, and many other forms and styles of language usage, fall under prose, a label that can describe both speech and writing. In writing, poetry is traditionally written in a special format called verse: a series of lines stacked on top of one another on a page. This parallels how a person might read the poem aloud; for example, poetry may end with a rhyme at the end of each line, making the entire work more melodious or memorable. Prose uses writing conventions and formatting that may highlight meaning—for instance, the use of a new paragraph for a new speaker in a novel—but does not follow any special rhythmic or other artistic structure.
        
        The word \"prose\" first appeared in English in the 14th century. It is derived from the Old French prose, which in turn originates in the Latin expression prosa oratio (literally, straightforward or direct speech).[1] In highly-literate cultures where spoken rhetoric is considered relatively unimportant, definitions of prose may be narrower, including only written language (but including written speech or dialogue). In written languages, spoken and written prose usually differ sharply. Sometimes, these differences are transparent to those using the languages; linguists studying extremely literal transcripts for conversation analysis see them, but ordinary language-users are unaware of them.
        
        Academic writing (works of philosophy, history, economics, etc.), journalism, and fiction are usually written in prose (excepting verse novels etc.). Developments in 20th century literature, including free verse, concrete poetry, and prose poetry, have led to the idea of poetry and prose as two ends on a spectrum rather than firmly distinct from each other. The British poet T. S. Eliot noted, whereas \"the distinction between verse and prose is clear, the distinction between poetry and prose is obscure.\""
    );
    insertGenre($genre7, $conn);

    $genre8 = new Genre(
        "Romanticism",
        "Romanticism (also known as the Romantic movement or Romantic era) was an artistic and intellectual movement that originated in Europe towards the end of the 18th century. The purpose of the movement was to advocate for the importance of subjectivity, imagination, and appreciation of nature in society and culture in response to the Age of Enlightenment and the Industrial Revolution.

        Romanticists rejected the social conventions of the time in favour of a moral outlook known as individualism. They argued that passion and intuition were crucial to understanding the world, and that beauty is more than merely an affair of form, but rather something that evokes a strong emotional response. With this philosophical foundation, the Romanticists elevated several key themes to which they were deeply committed: a reverence for nature and the supernatural, an idealization of the past as a nobler era, a fascination with the exotic and the mysterious, and a celebration of the heroic and the sublime.
        
        The Romanticist movement had a particular fondness for the Middle Ages, which to them represented an era of chivalry, heroism, and a more organic relationship between humans and their environment. This idealization contrasted sharply with the values of their contemporary industrial society, which they considered alienating for its economic materialism and environmental degradation. The movement's illustration of the Middle Ages was a central theme in debates, with allegations that Romanticist portrayals often overlooked the downsides of medieval life.
        
        The consensus is that Romanticism peaked from 1800 until 1850. However, a \"Late Romantic\" period and \"Neoromantic\" revivals are also discussed. These extensions of the movement are characterized by a resistance to the increasingly experimental and abstract forms that culminated in modern art, and the deconstruction of traditional tonal harmony in music. They continued the Romantic ideal, stressing depth of emotion in art and music while showcasing technical mastery in a mature Romantic style. By the time of World War I, though, the cultural and artistic climate had changed to such a degree that Romanticism essentially dispersed into subsequent movements. Final Late Romanticist figures to maintain the Romantic ideals (i.e. Rachmaninoff) died in the 1940s. Though they were still widely respected, they were seen as anachronisms at that point.
        
        Romanticism was a complex movement with a variety of viewpoints that permeated Western civilization across the globe. The movement and its opposing ideologies mutually shaped each other over time. After its end, Romantic thought and art exerted a sweeping influence on art and music, speculative fiction, philosophy, politics, and environmentalism that has endured to the present day, although the modern notion of \"romanticization\" and the act of \"romanticizing\" something often has little to do with the historical movement."
    );
    insertGenre($genre8, $conn);

    $genre9 = new Genre(
        "Gothic fiction",
        "Gothic fiction, often referred to as Gothic horror (primarily in the 20th century), is a literary aesthetic of fear and haunting. The name of the genre is derived from the Renaissance-era use of the word gothic, as a pejorative term meaning medieval and barbaric, which itself originated from Gothic architecture and in turn the Goths.[1]

        The first work to be labelled as Gothic was Horace Walpole's 1764 novel The Castle of Otranto, later subtitled A Gothic Story. Subsequent 18th-century contributors included Clara Reeve, Ann Radcliffe, William Beckford, and Matthew Gregory Lewis. The Gothic influence continued into the early 19th century, with Romantic works by poets such as Samuel Taylor Coleridge and Lord Byron. Novelists such as Mary Shelley, Charles Maturin, Walter Scott, and E. T. A. Hoffmann frequently drew upon Gothic motifs in their works as well.
        
        Gothic aesthetics continued to be used in Victorian literature in novels by Charles Dickens and the Brontë sisters, as well as in works by the American writers Edgar Allan Poe and Nathaniel Hawthorne. Later, Gothic fiction evolved through well-known works like Carmilla (1872) by Sheridan Le Fanu, Strange Case of Dr Jekyll and Mr Hyde (1886) by Robert Louis Stevenson, The Picture of Dorian Gray (1890) by Oscar Wilde, Dracula (1897) by Bram Stoker, and The Beetle (1897) by Richard Marsh. In the 20th-century, Gothic fiction remained influential with contributors including Daphne du Maurier, Stephen King, V. C. Andrews, Shirley Jackson, Anne Rice, and Toni Morrison."
    );
    insertGenre($genre9, $conn);

    $genre10 = new Genre(
        "Horror fiction",
        "Horror is a genre of speculative fiction that is intended to disturb, frighten, or scare an audience. Horror is often divided into the sub-genres of psychological horror and supernatural horror. Literary historian J. A. Cuddon, in 1984, defined the horror story as \"a piece of fiction in prose of variable length ... which shocks, or even frightens the reader, or perhaps induces a feeling of repulsion or loathing\". Horror intends to create an eerie and frightening atmosphere for the reader. The threats present in horror fiction often reflect broader cultural anxieties."
    );
    insertGenre($genre10, $conn);

    $genre11 = new Genre(
        "Children",
        "Children's literature or juvenile literature includes stories, books, magazines, and poems that are created for children. In addition to conventional literary genres, modern children's literature is classified by the intended age of the reader, ranging from picture books for the very young to young adult fiction for those nearing maturity.

        Children's literature can be traced to traditional stories like fairy tales, which have only been identified as children's literature since the seventeenth century, and songs, part of a wider oral tradition, which adults shared with children before publishing existed. The development of early children's literature, before printing was invented, is difficult to trace. Even after printing became widespread, many classic \"children's\" tales were originally created for adults and later adapted for a younger audience. Since the fifteenth century much literature has been aimed specifically at children, often with a moral or religious message. Children\'s literature has been shaped by religious sources, like Puritan traditions, or by more philosophical and scientific standpoints with the influences of Charles Darwin and John Locke.[2] The late nineteenth and early twentieth centuries are known as the \"Golden Age of Children's Literature\" because many classic children's books were published then."
    );
    insertGenre($genre11, $conn);

    $genre12 = new Genre(
        "Historical fiction",
        "Historical fiction is a literary genre in which a fictitious plot takes place in the setting of particular real historical events. Although the term is commonly used as a synonym for historical fiction literature, it can also be applied to other types of narrative, including theatre, opera, cinema, and television, as well as video games and graphic novels.

        An essential element of historical fiction is that it is set in the past and pays attention to the manners, social conditions and other details of the depicted period.[1] Authors also frequently choose to explore notable historical figures in these settings, allowing readers to better understand how these individuals might have responded to their environments. The historical romance usually seeks to romanticize eras of the past. Some subgenres such as alternate history and historical fantasy insert intentionally ahistorical or speculative elements into a novel.
        
        Works of historical fiction are sometimes criticized for lack of authenticity because of readerly criticism or genre expectations for accurate period details. This tension between historical authenticity and fiction frequently becomes a point of comment for readers and popular critics, while scholarly criticism frequently goes beyond this commentary, investigating the genre for its other thematic and critical interests.
        
        Historical fiction as a contemporary Western literary genre has its foundations in the early-19th-century works of Sir Walter Scott and his contemporaries in other national literatures such as the Frenchman Honoré de Balzac, the American James Fenimore Cooper, and later the Russian Leo Tolstoy. However, the melding of historical and fictional elements in individual works of literature has a long tradition in many cultures, including both Western traditions (as early as Ancient Greek and Latin literature) and Eastern, in the form of oral and folk traditions (see mythology and folklore), which produced epics, novels, plays, and other fictional works describing history for contemporary audiences"
    );
    insertGenre($genre12, $conn);

    $genre12 = new Genre(
        "Adventure Fiction",
        "Adventure fiction is a type of fiction that usually presents danger, or gives the reader a sense of excitement. Some adventure fiction also satisfies the literary definition of romance fiction."
    );
    insertGenre($genre12, $conn);
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

    $author6 = new Author();
    $author6->Name = "Charlotte Brontë";
    $author6->Description = "Charlotte Nicholls (21 April 1816 – 31 March 1855), commonly known by her maiden name Charlotte Brontë, was an English novelist and poet, and was the elder sister of Emily, Anne and Branwell Brontë. She is best known for her novel Jane Eyre, which was first published under the pseudonym Currer Bell. Jane Eyre was a great success on publication, and has since been acknowledged as a classic of English literature.

    Charlotte was the third of six siblings born to Maria Branwell and Patrick Brontë. Maria died when Charlotte was only five years old, and three years later, Charlotte was sent to the Clergy Daughters' School at Cowan Bridge in Lancashire, along with her three sisters, Maria, Elizabeth and Emily. Conditions at the school were appalling, with frequent outbreaks of disease. Charlotte's two elder sisters fell ill there and died shortly afterwards; Charlotte attributed her own lifelong ill-health to her time at Cowan Bridge, and later used it as the model for Lowood School in Jane Eyre.
    
    In 1831, Charlotte became a pupil at Roe Head School in Mirfield, but left the following year to teach her sisters, Emily and Anne, at home. In 1835, Charlotte returned to Roe Head as a teacher. In 1839, she accepted a job as governess to a local family, but left after a few months. In 1842, Charlotte joined the Heger Pensionnat, a girls' boarding school in Brussels, as a student, then later as a teacher, in the hope of acquiring the skills required to open a school of her own. However, she was obliged to leave after falling in love with the school's director, Constantin Heger, a married man, who inspired both the character of Rochester in Jane Eyre, and Charlotte's first novel, The Professor.
    
    Charlotte, Emily and Anne attempted to open a school in Haworth, but failed to attract pupils. In 1846 the sisters published a collection of poems under the pseudonyms Currer, Ellis, and Acton Bell. Although Charlotte's first novel, The Professor, was rejected by publishers, her second novel, Jane Eyre, was published in 1847, attracting both praise and controversy. The sisters' true identities were revealed in 1848, and by the following year Charlotte was known in London literary circles. In 1854, Charlotte married Arthur Bell Nicholls, her father's curate. She became pregnant shortly after her wedding in June 1854, but died on 31 March 1855, possibly of tuberculosis, although there is evidence that she may have died from hyperemesis gravidarum, a complication of pregnancy.";
    $author6->ImagePath = "Charlotte_Brontë_by_Patrick_Branwell_Brontë_restored.jpg";
    insertAuthor($author6, $conn);

    $author7 = new Author();
    $author7->Name = "Emily Brontë";
    $author7->Description = "Emily Jane Brontë (30 July 1818 – 19 December 1848) was an English writer best known for her 1847 novel Wuthering Heights. She also co-authored a book of poetry with her sisters Charlotte and Anne entitled Poems by Currer, Ellis, and Acton Bell.

    Emily was the fifth of six Brontë siblings, four of whom survived into adulthood. Her mother, Maria Branwell, died when she was three, leaving the children in the care of their aunt, Elizabeth Branwell. Apart from brief intervals at school, Emily was mostly taught at home by her father, Patrick Brontë, who was the curate of Haworth. She was very close to her siblings, especially her younger sister Anne, and together they wrote books and journals depicting imaginary worlds named Glass Town, Angria and Gondal. She was described by her sister Charlotte as solitary, strong-willed and nonconforming, with a keen love of nature and animals.
    
    Apart from a brief period at school, and another as a student and teacher in Brussels with her sister Charlotte, Emily spent most of her life at home in Haworth, helping the family servant with chores, playing the piano and teaching herself from books.
    
    Her work was originally published under the pen name Ellis Bell. It was not generally admired at the time, and many critics felt that the characters in Wuthering Heights were coarse and immoral. In spite of this, the novel is considered a classic of English literature. Emily Brontë died in 1848, aged 30, a year after the novel's publication.";
    $author7->ImagePath = "Emily_Brontë_by_Patrick_Branwell_Brontë_restored.jpg.webp";
    insertAuthor($author7, $conn);

    $author8 = new Author();
    $author8->Name = "Bram Stoker";
    $author8->Description = "Abraham Stoker (8 November 1847 – 20 April 1912) was an Irish writer, barrister, literary critic, and theatre manager. He was the author of the Gothic horror novel Dracula (1897) and the creator of the fictional character Count Dracula. The work and its antagonist are regarded as milestones in the fields of Gothic and vampire literature.

    Stoker was the third of seven children and was bedridden for the first seven years of his life with an undiagnosed illness. He received his initial education at home, before enrolling at Trinity College Dublin in 1864. He excelled as a rugby athlete and was a prominent member of both the university's student union and its philosophical society. It was during this period that Stoker was initially introduced to literature, eventually becoming the auditor of the College Historical Society and writing his first paper.
    
    At the outset of his career, Stoker spent ten years in the civil service at Dublin Castle, during which time he was also a drama critic for the Dublin Evening Mail. Following this, he was employed as a theatre critic for several newspapers, including the Daily Telegraph, and occasionally wrote short stories and theatre commentaries. He also served as the personal assistant of the actor Sir Henry Irving and the business manager of the West End's Lyceum Theatre, which Irving owned. While a theatre critic, Stoker founded the \"Dublin Sketching Club\" in 1879, which dealt mostly with art collections. He regularly travelled during his free time, particularly to Cruden Bay in Scotland, which was the setting for two of his novels and also served as the inspiration for writing Dracula. He was a close friend of Hall Caine and an acquaintance of Arthur Conan Doyle, Oscar Wilde and many others prominent cultural figures. He collaborated with other authors for experimental novels such as The Fate of Fenella (1892).
    
    Stoker wrote numerous works of fiction and non-fiction across several genres, but his literary reputation rests solely with Dracula. Since the early 20th century, the novel has become one of the best-selling works of vampire fiction, with Count Dracula entering the public imagination as one of the best-known fictional figures of the Victorian era. The work also shaped future representations of vampiric characters and themes, making Stoker \"the father of vampire fiction.\"";
    $author8->ImagePath = "Bram_Stoker_1906.jpg";
    insertAuthor($author8, $conn);

    $author9 = new Author();
    $author9->Name = "Mary Shelley";
    $author9->Description = "Mary Wollstonecraft Shelley (née Godwin; 30 August 1797 – 1 February 1851) was an English novelist who wrote the Gothic novel Frankenstein; or, The Modern Prometheus (1818), which is considered an early example of science fiction. She also edited and promoted the works of her husband, the Romantic poet and philosopher Percy Bysshe Shelley. Her father was the political philosopher William Godwin and her mother was the philosopher and women's rights advocate Mary Wollstonecraft.

    Mary's mother died 11 days after giving birth to her. She was raised by her father, who provided her with a rich informal education, encouraging her to adhere to his own anarchist political theories. When she was four, her father married a neighbour, Mary Jane Clairmont, with whom Mary had a troubled relationship.
    
    In 1814, Mary began a romance with one of her father's political followers, Percy Bysshe Shelley, who was already married. Together with her stepsister, Claire Clairmont, she and Percy left for France and travelled through Europe. Upon their return to England, Mary was pregnant with Percy's child. Over the next two years, she and Percy faced ostracism, constant debt and the death of their daughter. They married in late 1816, after the suicide of Percy's wife, Harriet.
    
    In 1816, the couple and Mary's stepsister famously spent a summer with Lord Byron and John William Polidori near Geneva, Switzerland, where Mary conceived the idea for her novel Frankenstein. The Shelleys left Britain in 1818 for Italy, where their second and third children died before Mary gave birth to her last and only surviving child, Percy Florence Shelley. In 1822, her husband drowned when his sailing boat sank during a storm near Viareggio. A year later, Mary returned to England and from then on devoted herself to raising her son and to her career as a professional author. The last decade of her life was dogged by illness, most likely caused by the brain tumour which killed her at the age of 53.
    
    Until the 1970s, Mary Shelley was known mainly for her efforts to publish her husband's works and for her novel Frankenstein, which remains widely read and has inspired many theatrical and film adaptations. Recent scholarship has yielded a more comprehensive view of her achievements. Scholars have shown increasing interest in her literary output, particularly in her novels, which include the historical novels Valperga (1823) and Perkin Warbeck (1830), the apocalyptic novel The Last Man (1826) and her final two novels, Lodore (1835) and Falkner (1837). Studies of her lesser-known works, such as the travel book Rambles in Germany and Italy (1844) and the biographical articles for Dionysius Lardner's Cabinet Cyclopaedia (1829–1846), support the growing view that Mary Shelley remained a political radical throughout her life. Mary Shelley's works often argue that cooperation and sympathy, particularly as practised by women in the family, were the ways to reform civil society. This view was a direct challenge to the individualistic Romantic ethos promoted by Percy Shelley and the Enlightenment political theories articulated by Mary's father, William Godwin.";
    $author9->ImagePath = "Mary_Wollstonecraft_Shelley_Rothwell.tif.jpg";
    insertAuthor($author9, $conn);

    $author10 = new Author();
    $author10->Name = "Lewis Carroll";
    $author10->Description = "Charles Lutwidge Dodgson (27 January 1832 – 14 January 1898), better known by his pen name Lewis Carroll, was an English author, poet, mathematician, photographer, and Anglican deacon. His most notable works are Alice's Adventures in Wonderland (1865) and its sequel Through the Looking-Glass (1871), some of the most important examples of Victorian literature. He was noted for his facility with word play, logic, and fantasy. His poems Jabberwocky (1871) and The Hunting of the Snark (1876) are classified in the genre of literary nonsense. Some of Alice's nonsensical wonderland logic reflects his published work on mathematical logic.

    Carroll came from a family of high-church Anglicans and pursued his clerical training at Christ Church, Oxford, where he lived for most of his life as a scholar, teacher and Anglican deacon (required for his academic fellowship at the time). Alice Liddell – a daughter of Henry Liddell, the Dean of Christ Church – is widely identified as the original inspiration for Alice in Wonderland, although Carroll always denied this.
    
    An avid puzzler, Carroll created the word ladder puzzle, which he called \"Doublets\", which was published in Vanity Fair magazine between 1879 and 1881. In 1982 a memorial stone to Carroll was unveiled at Poets' Corner in Westminster Abbey. There are societies in many parts of the world dedicated to the enjoyment and promotion of his works.";
    $author10->ImagePath = "LewisCarrollSelfPhoto.jpg";
    insertAuthor($author10, $conn);

    $author11 = new Author();
    $author11->Name = "Frances Hodgson Burnett";
    $author11->Description = "Frances Eliza Hodgson Burnett (24 November 1849 – 29 October 1924) was an English-American[1] novelist and playwright. She is best known for the three children's novels Little Lord Fauntleroy (1886), A Little Princess (1905), and The Secret Garden (1911).

    Hodgson was born in Cheetham, Manchester, England. After her father died in 1853, when Frances was only four years old, the family fell on strained circumstances and emigrated to the United States 12 years later, settling in New Market, Tennessee. Frances began her writing career there at age 19 to help earn money for the family, publishing stories in magazines. In 1870, her mother died. Three years later, Hodgson married Swan Burnett in Knoxville, Tennessee, who became a medical doctor. Their first son, Lionel, was born a year later. The Burnetts lived for two years in Paris, where their second son, Vivian, was born, before returning to the United States to live in Washington, D.C. Burnett then began to write novels, the first of which (That Lass o' Lowrie's), was published to good reviews. Little Lord Fauntleroy was published in 1886 and made her a popular writer of children's fiction, although her romantic adult novels written in the 1890s were also popular. She wrote and helped to produce stage versions of Little Lord Fauntleroy and A Little Princess.
    
    Beginning in the 1880s, Burnett began to travel to England frequently and in the 1890s bought a home there, where she wrote The Secret Garden. Burnett's elder son, Lionel, died of tuberculosis in 1890, which caused a relapse of the depression she had struggled with for much of her life. Burnett divorced her husband in 1898 and married Stephen Townsend two years later, but they divorced in 1902. A few years later, Burnett settled in Nassau County, New York, where she died in 1924 and is buried in Roslyn Cemetery.
    
    In 1936, a memorial sculpture by Bessie Potter Vonnoh was erected in Burnett's honour in Central Park's Conservatory Garden. The statue depicts her two famous Secret Garden characters, Mary and Dickon.";
    $author11->ImagePath = "Frances_Burnett.jpg";
    insertAuthor($author11, $conn);

    $author12 = new Author();
    $author12->Name = "Robert Louis Stevenson";
    $author12->Description = "Robert Louis Stevenson (born Robert Lewis Balfour Stevenson; 13 November 1850 – 3 December 1894) was a Scottish novelist, essayist, poet and travel writer. He is best known for the novels Treasure Island (1883), Strange Case of Dr Jekyll and Mr Hyde (1886), and Kidnapped (1886) and for the poetry collection A Child's Garden of Verses (1885).

    Born and educated in Edinburgh, Stevenson suffered from serious bronchial trouble for much of his life but managed to write prolifically and travel widely despite his poor health. As a young man, he mixed in London literary circles, receiving encouragement from Sidney Colvin, Andrew Lang, Edmund Gosse, Leslie Stephen and W. E. Henley, the last of whom may have provided the model for Long John Silver in Treasure Island. In 1890 he settled in Samoa, where, alarmed at increasing European and American influence in the South Sea islands, his writing turned from romance and adventure fiction toward a darker realism. He died of a stroke in his island home in 1894 at age 44.
    
    A celebrity in his lifetime, Stevenson's critical reputation has fluctuated since his death, although today his works are held in general acclaim. In 2018 he was ranked just behind Charles Dickens as the 26th-most-translated author in the world.";
    $author12->ImagePath = "Robert_Louis_Stevenson_by_Henry_Walter_Barnett_bw.jpg";
    insertAuthor($author12, $conn);
}

function createBooks(PDO $conn)
{
    $book1 = new Book();
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
    $book3->Title = "The Two Towers";
    $book3->Author = 1;
    $book3->Genres = [5];
    $book3->Price = 7.99;
    $book3->ImagePath = "the_two_towers.png";
    $book3->StockCount = 30;
    $book3->Description = "The Two Towers is the second volume of J. R. R. Tolkien's high fantasy novel The Lord of the Rings. It is preceded by The Fellowship of the Ring and followed by The Return of the King.";
    insertBook($book3, $conn);

    $book4 = new Book();
    $book4->Title = "The Return of the King";
    $book4->Author = 1;
    $book4->Genres = [5];
    $book4->Price = 7.99;
    $book4->ImagePath = "the_return_of_the_king.png";
    $book4->StockCount = 30;
    $book4->Description = "The Return of the King is the third and final volume of J. R. R. Tolkien's The Lord of the Rings, following The Fellowship of the Ring and The Two Towers. It was published in 1955. The story begins in the kingdom of Gondor, which is soon to be attacked by the Dark Lord Sauron.";
    insertBook($book4, $conn);

    $book5 = new Book();
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
    $book6->Title = "Unfinished Tales of Numenor and Middle-Earth";
    $book6->Author = 1;
    $book6->Genres = [5];
    $book6->Price = 7.99;
    $book6->ImagePath = "unfinished_tales_of_numenor_middle_earth.jpg";
    $book6->StockCount = 30;
    $book6->Description = "Unfinished Tales of Númenor and Middle-earth is a collection of stories and essays by J. R. R. Tolkien that were never completed during his lifetime, but were edited by his son Christopher Tolkien and published in 1980. Many of the tales within are retold in The Silmarillion, albeit in modified forms; the work also contains a summary of the events of The Lord of the Rings told from a less personal perspective.";
    insertBook($book6, $conn);

    $book7 = new Book();
    $book7->Title = "The Fall of Numenor";
    $book7->Author = 1;
    $book7->Genres = [5];
    $book7->Price = 7.99;
    $book7->ImagePath = "fall_of_numenor.jpg";
    $book7->StockCount = 30;
    $book7->Description = "The Fall of Númenor is a fantasy work collecting all J. R. R. Tolkien's Second Age writings together. It is edited by Brian Sibley. The book uses \"The Tale of Years\" in the Appendices of The Lord of the Rings to present the content of the Second Age. The stories include the foundation of Númenor, the forging of the Rings of Power, and the Last Alliance against Sauron that ended the Second Age. The editor, Brian Sibley, has provided new introductions and commentaries to unify Tolkien's writings. The publication contains ten new colour paintings by Alan Lee. The book published on 15 November 2022.";
    insertBook($book7, $conn);

    $book8 = new Book();
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
    $book18->Title = "The Ballad of Songbirds and Snakes";
    $book18->Author = 3;
    $book18->Genres = [3, 4];
    $book18->Price = 7.99;
    $book18->ImagePath = "ballad_of_songbirds_and_snakes.jpg";
    $book18->StockCount = 30;
    $book18->Description = "The Ballad of Songbirds and Snakes is a dystopian action-adventure novel by American author Suzanne Collins. It is a spin-off and a prequel to The Hunger Games trilogy. It was released on May 19, 2020, by Scholastic. An audiobook of the novel read by American actor Santino Fontana was released simultaneously with the printed edition. The book received a virtual launch due to the COVID-19 pandemic. A film adaptation from Lionsgate is set to be released on November 17, 2023.";
    insertBook($book18, $conn);

    $book19 = new Book();
    $book19->Title = "A Game of Thrones";
    $book19->Author = 4;
    $book19->Genres = [5];
    $book19->Price = 12.50;
    $book19->ImagePath = "a_game_of_thrones.jpg";
    $book19->StockCount = 30;
    $book19->Description = "The first volume in the A Song of Ice and Fire series – unquestionably one of the greatest fantasy epics of all time. Here, we are introduced to the Seven Kingdoms of Westeros, and the major players with eyes on its Iron Throne. As noble families battle for power, greater threats face them all: for winter is coming to Westeros, and here, winter can last a lifetime.";
    insertBook($book19, $conn);

    $book20 = new Book();
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

    $book27 = new Book();
    $book27->Title = "Jane Eyre";
    $book27->Author = 6;
    $book27->Genres = [6];
    $book27->Price = 7.99;
    $book27->ImagePath = "jane_eyre.jpg";
    $book27->StockCount = 30;
    $book27->Description = "Jane Eyre (originally published as Jane Eyre: An Autobiography) is a novel by the English writer Charlotte Brontë. It was published under her pen name \"Currer Bell\" on 19 October 1847 by Smith, Elder & Co. of London. The first American edition was published in January 1848 by Harper & Brothers of New York. Jane Eyre is a bildungsroman that follows the experiences of its eponymous heroine, including her growth to adulthood and her love for Mr Rochester, the brooding master of Thornfield Hall.

    The novel revolutionised prose fiction, being the first to focus on the moral and spiritual development of its protagonist through an intimate first-person narrative, where actions and events are coloured by a psychological intensity. Literary critic Daniel S. Burt has called Charlotte Brontë \"the first historian of the private consciousness\" and the literary ancestor of writers such as Marcel Proust and James Joyce.
    
    The book contains elements of social criticism with a strong sense of Christian morality at its core, and it is considered by many to be ahead of its time because of Jane's individualistic character and how the novel approaches the topics of class, sexuality, religion and feminism. Jane Eyre, along with Jane Austen's Pride and Prejudice, is one of the most famous romance novels. It is considered one of the greatest novels in the English language, and in 2003 was ranked as the tenth best-loved book in Britain by the BBC in The Big Read poll.";
    insertBook($book27, $conn);

    $book28 = new Book();
    $book28->Title = "Wuthering Heights";
    $book28->Author = 7;
    $book28->Genres = [8, 9];
    $book28->Price = 7.99;
    $book28->ImagePath = "wuthering_heights.jpg";
    $book28->StockCount = 30;
    $book28->Description = "Wuthering Heights is the only novel by the English author Emily Brontë, initially published in 1847 under her pen name Ellis Bell. It concerns two landowning families on the West Yorkshire moors, the Earnshaws and the Lintons, and their turbulent relationships with the Earnshaws' foster son, Heathcliff. Driven by themes of love, possession, revenge, and reconciliation, the novel is influenced by Romanticism and Gothic fiction. It is considered a classic of English literature.

    Wuthering Heights was accepted by publisher Thomas Newby along with Anne Brontë's Agnes Grey before the success of their sister Charlotte Brontë's novel Jane Eyre, but Jane Eyre was published before the other two. The first American edition was published in April 1848 by Harper & Brothers of New York.After Emily's death, Charlotte edited a second edition of Wuthering Heights, which was published in 1850.
    
    Though contemporary reviews were polarised, Wuthering Heights has come to be considered one of the greatest novels written in English. It was controversial for its depictions of mental and physical cruelty, including domestic abuse, and for its challenges to Victorian morality, religion, and the class system. It has inspired an array of adaptations across several types of media.";
    insertBook($book28, $conn);

    $book29 = new Book();
    $book29->Title = "Dracula";
    $book29->Author = 8;
    $book29->Genres = [9, 10];
    $book29->Price = 7.99;
    $book29->ImagePath = "dracula.jpg";
    $book29->StockCount = 30;
    $book29->Description = "Dracula is an 1897 Gothic horror novel by Irish author Bram Stoker. The narrative is related through letters, diary entries, and newspaper articles. It has no single protagonist and opens with English solicitor Jonathan Harker taking a business trip to stay at the castle of a Transylvanian nobleman, Count Dracula. Revealing his true nature as a vampire, Dracula moves to England and plagues the seaside town of Whitby. Harker joins a group led by Abraham Van Helsing who hunt and kill the Count.

    The novel was mostly written in the 1890s, and Stoker produced over a hundred pages of notes, drawing extensively from folklore and history. Scholars have suggested various figures as the inspiration for Dracula, including the Wallachian prince Vlad the Impaler and the Countess Elizabeth Báthory, but recent scholarship suggests otherwise. He found the name \"Dracula\" in a history of Wallachia and Moldavia, selecting it because he thought it meant 'devil' in Romanian.
    
    Following the novel's publication in May 1897, some reviewers praised its terrifying atmosphere while others thought Stoker included too much horror. Many noted a structural similarity with Wilkie Collins' The Woman in White (1859) and a resemblance to the work of Gothic novelist Ann Radcliffe. In the 20th century, Dracula became regarded by critics as a seminal work of Gothic fiction. Scholars explore the novel within the historical context of the Victorian era and regularly discuss its portrayal of race, religion, gender and sexuality.
    
    Dracula is one of the most famous works of English literature and has been called the centrepiece of vampire fiction. In the mid-20th century, publishers and film-makers realised Stoker incorrectly filed the novel's copyright in the United States, making its story and characters public domain there. Consequently, the novel has been adapted many times. Count Dracula has deeply influenced the popular conception of vampires; with over 700 appearances across virtually all forms of media, the Guinness Book of World Records named Dracula the most portrayed literary character. ";
    insertBook($book29, $conn);

    $book30 = new Book();
    $book30->Title = "Frankenstein";
    $book30->Author = 9;
    $book30->Genres = [8, 9];
    $book30->Price = 7.99;
    $book30->ImagePath = "frankenstein.jpg";
    $book30->StockCount = 30;
    $book30->Description = "Frankenstein; or, The Modern Prometheus is an 1818 Gothic novel[a] written by English author Mary Shelley. It tells the story of Victor Frankenstein, a young scientist who creates a sapient creature from different body parts in an unorthodox scientific experiment. Shelley started writing the story when she was 18 and staying in Bath, and the first edition was published anonymously in London on 1 January 1818, when she was 20. Her name first appeared in a French translation published in Paris in 1821.

    Shelley travelled through Europe in 1815, moving along the river Rhine in Germany, and stopping in Gernsheim, 17 kilometres (11 mi) away from Frankenstein Castle, where, about a century earlier, Johann Konrad Dippel, an alchemist, had engaged in experiments. She then journeyed to the region of Geneva, Switzerland, where much of the story takes place. Galvanism and occult ideas were topics of conversation for her companions, particularly for her lover and future husband, Percy Bysshe Shelley.
    
    In 1816—at the suggestion of Lord Byron—Mary, Percy, John Polidori and Byron himself, each agreed to try writing a ghost story. After thinking for days, Shelley was inspired to write Frankenstein after imagining a scientist who created life and was horrified by what he had made. The novel was first published anonymously in 1818, and in 1831, a revised edition was published under Mary Shelley's name. This version included significant stylistic revisions, a new preface describing the story's conception, and a more explicitly moral tone.
    
    Frankenstein is one of the best-known works of English literature. Infused with elements of the Gothic novel and the Romantic movement, it has had a considerable influence on literature and on popular culture, spawning a complete genre of horror stories, films, and plays. Since the publication of the novel, the name Frankenstein has often been used, erroneously, to refer to the monster rather than the man who created it.";
    insertBook($book30, $conn);

    $book31 = new Book();
    $book31->Title = "Alice's Adventures in Wonderland";
    $book31->Author = 10;
    $book31->Genres = [1];
    $book31->Price = 7.99;
    $book31->ImagePath = "alices_adventures_in_wonderland.webp";
    $book31->StockCount = 30;
    $book31->Description = "Alice's Adventures in Wonderland (also known as Alice in Wonderland) is an 1865 English children's novel by Lewis Carroll. It tells the story of a little girl named Alice who falls through a rabbit hole into a fantasy world of anthropomorphic creatures. It is seen as an example of the literary nonsense genre. The artist John Tenniel provided 42 wood-engraved illustrations for the original edition.

    The novel received positive reviews upon release. It has become one of the best-known works of Victorian literature; its narrative, structure, characters, and imagery have had a wide influence on popular culture and literature, especially in the fantasy genre.[1][2] It is credited as helping end an era of didacticism in children's literature, inaugurating an era in which writing for children aimed to \"delight or entertain\". The tale plays with logic, giving the story lasting popularity with adults as well as with children. The titular character, Alice, shares her name with Alice Liddell, a girl Carroll knew; scholars disagree about the extent to which the character was based upon her.
    
    The novel has never been out of print and has been translated into 174 languages. Its legacy includes adaptations to screen, radio, visual art, ballet, opera, and musical theatre as well as theme parks, board games, and video games.[7] Carroll published a sequel in 1871 entitled Through the Looking-Glass and a shortened version for young children, The Nursery \"Alice\", in 1890.";
    insertBook($book31, $conn);

    $book32 = new Book();
    $book32->Title = "The Secret Garden";
    $book32->Author = 11;
    $book32->Genres = [11];
    $book32->Price = 7.99;
    $book32->ImagePath = "the_secret_garden.jpg";
    $book32->StockCount = 30;
    $book32->Description = "The Secret Garden is a children's novel by Frances Hodgson Burnett first published in book form in 1911, after serialisation in The American Magazine (November 1910 – August 1911). Set in England, it is seen as a classic of English children's literature. The American edition was published by the Frederick A. Stokes Company with illustrations by M. L. Kirk, and the British edition by Heinemann with illustrations by Charles Robinson. Several stage and film adaptations have been made of The Secret Garden.";
    insertBook($book32, $conn);

    $book33 = new Book();
    $book33->Title = "Treasure Island";
    $book33->Author = 12;
    $book33->Genres = [12, 13];
    $book33->Price = 7.99;
    $book33->ImagePath = "treasure_island.jpg";
    $book33->StockCount = 30;
    $book33->Description = "Treasure Island is an adventure and historical novel by Scottish novelist Robert Louis Stevenson. It was published as a book in 1883, but is set in the 18th century, and tells a story of \"buccaneers and buried gold\". It is considered a coming-of-age story, and is noted for its atmosphere, characters, and action.

    The author used the working title The Sea Cook: A Story for Boys, and the pseudonym \"Captain George North\". When it was first published as a serial from 1881 to 1882 in the children's magazine Young Folks the title became Treasure Island or the Mutiny of the Hispaniola. It was first published as a book on 14 November 1883 by Cassell & Co. It has since become one of the most-often dramatised and adapted novels.
    
    Since its publication Treasure Island has significantly influenced depictions of pirates in popular culture, including elements such as deserted tropical islands, treasure maps marked with an \"X\", and one-legged seamen with parrots perched on their shoulders.";
    insertBook($book33, $conn);
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
        CREATE TABLE IF NOT EXISTS bookstore.cart_items (
            cart_item_id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            user_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES bookstore.users(user_id),
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
