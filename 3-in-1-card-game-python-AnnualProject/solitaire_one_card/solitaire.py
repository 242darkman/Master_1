from random import shuffle

import pygame as pg
from pygame.locals import *
from pygame import Surface

from cardsPackage.cards import Cards
from cardsPackage.gameStateEnum import GameState
from cardsPackage.score import Score
from cardsPackage.stack.FondationP import FoundationStack
from cardsPackage.stack.StockageP import StockageStack
from cardsPackage.stack.TableauP import TableauStack
from cardsPackage.stack.WasteP import WasteStack
from cardsPackage.suitsEnum import Suits
from cardsPackage.cardImage import CardImage


class Solitaire:
    game_state = GameState.GAME
    should_quit = False

    def __init__(self, spacing, tableau_spacing):
        pg.init()
        self.screen = pg.display.set_mode((500, 500))
        pg.display.set_caption("Solitaire 1 carte")

        CardImage.load_images("red3", 0.7)
        card_width = CardImage.card_width
        card_height = CardImage.card_height

        width = 8 * spacing + 7 * int(card_width)
        height = 2 * spacing + int(card_height) + 600
        self.screen = pg.display.set_mode((width, height))
        self.score = 0

        self.dragged_cards_pile = None
        self.dragged_cards = []

        self.background = Surface(self.screen.get_size())
        self.background = self.background.convert()
        self.background.fill((66, 163, 78))

        cards = []
        for suit in Suits:
            for value in range(1, 14):
                cards.append((Cards(suit, value, False)))
        shuffle(cards)

        # Stock pile
        self.stock = StockageStack(Rect(spacing, spacing, card_width, card_height), CardImage.pile_card_image)

        # Waste pile
        self.waste = WasteStack(Rect(2 * spacing + card_width, spacing, card_width, card_height),
                                0)

        # Foundation piles
        self.foundations = []
        for i in range(0, 4):
            foundation_rect = Rect(4 * spacing + 3 * card_width + (spacing + card_width) * i, spacing, card_width,
                                   card_height)
            self.foundations.append(FoundationStack(foundation_rect, CardImage.pile_card_image))

        # Tableau piles
        self.tablo = []
        index = 0
        for i in range(0, 7):
            tableau_rect = Rect(spacing + (spacing + card_width) * i, 2 * spacing + card_height, card_width,
                                card_height)
            self.tablo.append(TableauStack(tableau_rect, CardImage.pile_card_image, tableau_spacing))
            for j in range(0, i + 1):
                self.tablo[i].add_card(cards[index])
                index += 1

        # Add cards to stock
        for i in range(28, 52):
            self.stock.add_card(cards[i])

    def update(self):
        # print(self.dragged_cards)
        for event in pg.event.get():
            if event.type == QUIT:
                self.should_quit = True
            elif event.type == MOUSEBUTTONDOWN:
                if not self.dragged_cards:
                    if self.stock.collidepoint(event.pos):
                        if not self.stock.is_empty():
                            cards_from_stock = self.stock.get_cards()
                            for card in cards_from_stock:
                                card.show()  # afficher les cartes dans la pile de stockage lorsqu'on clique pour
                                # piocher une carte
                                self.waste.add_card(card)
                        else:
                            cards_from_waste = self.waste.get_cards()
                            for card in cards_from_waste:
                                card.hide()  # cacher les cartes dans notre poubelle après récupération des cartes
                                # dans notre poubelle
                                self.stock.add_card(card)
                    draggable_stack = self.tablo + self.foundations + [self.waste]
                    for pile in draggable_stack:
                        if pile.collidepoint(event.pos):
                            self.dragged_cards = pile.start_drag(event.pos)
                            if self.dragged_cards:
                                self.dragged_cards_pile = pile
                            break
            elif event.type == MOUSEMOTION:
                mouse_pos = pg.mouse.get_rel()
                for sprite in self.dragged_cards:
                    sprite.move(mouse_pos)

            elif event.type == MOUSEBUTTONUP:
                if self.dragged_cards:
                    for tableau in self.tablo:
                        if tableau.collidepoint(event.pos):
                            if tableau.drop(self.dragged_cards):
                                self.dragged_cards = []
                            break
                    if len(self.dragged_cards) == 1:
                        for foundation in self.foundations:
                            if foundation.collidepoint(event.pos):
                                if foundation.drop(self.dragged_cards[0]):
                                    self.dragged_cards = []
                                    self.score += 8
                                break
                    for card in self.dragged_cards:
                        self.dragged_cards_pile.add_card(card)
                    self.dragged_cards = []
                    self.dragged_cards_pile.end_drag()
                    self.dragged_cards_pile = None

        self.stock.update()
        for tableau in self.tablo:
            tableau.update()

    def render(self):
        self.screen.blit(self.background, (0, 0))

        self.stock.draw(self.screen)
        self.waste.drawCard(self.screen)

        score = Score(self.screen, 500, 550)
        score.message_display(self.score)

        for foundation in self.foundations:
            foundation.draw(self.screen)

        for tableau in self.tablo:
            tableau.draw(self.screen)

        for card_sprite in self.dragged_cards:
            self.screen.blit(card_sprite.image, card_sprite.rect)

        pg.display.flip()

    def run(self):
        while not self.should_quit:
            self.update()
            self.render()
