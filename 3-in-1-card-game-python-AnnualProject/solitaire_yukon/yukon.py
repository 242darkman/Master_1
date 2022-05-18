import random
from random import shuffle

import pygame as pg
from pygame.locals import *
from pygame import Surface

from cardsPackage.cards import Cards
from cardsPackage.gameStateEnum import GameState
from cardsPackage.stack.FondationP import FoundationStack
from cardsPackage.stack.TableauP import TableauStack
from cardsPackage.suitsEnum import Suits
from cardsPackage.cardImage import CardImage
from cardsPackage.score import Score


class Yukon:
    game_state = GameState.GAME
    want_to_quit = False

    def __init__(self, spacing, tableau_spacing):
        pg.init()
        self.screen = pg.display.set_mode((600, 600))
        pg.display.set_caption("Solitaire Yukon")

        CardImage.load_images("blue3", 0.7)
        card_width = CardImage.card_width
        card_height = CardImage.card_height

        width = 8 * spacing + 7 * int(card_width) + 200
        # print("Width --> ", width)
        height = 2 * spacing + int(card_height) + 600
        # print("Height --> ", height)
        self.screen = pg.display.set_mode((width, height))

        self.dragged_cards_pile = None
        self.dragged_cards = []
        self.score = 0

        self.background = Surface(self.screen.get_size())
        self.background = self.background.convert()
        self.background.fill((66, 163, 78))

        cards = []
        for suit in Suits:
            for value in range(1, 14):
                cards.append((Cards(suit, value, False)))

        # Rect(x, y, width, height)
        # Foundation piles
        self.foundations = []
        for col in range(0, 4):
            if col == 0:
                foundation_rect_1 = Rect(spacing + 15, spacing + 15, card_width, card_height)
                self.foundations.append(FoundationStack(foundation_rect_1, CardImage.pile_card_image))
            if col == 1:
                foundation_rect_2 = Rect(spacing + 15, spacing * col + card_width + 75,
                                         card_width, card_height)
                self.foundations.append(FoundationStack(foundation_rect_2, CardImage.pile_card_image))
            if col == 2:
                foundation_rect_3 = Rect(spacing + 15, spacing * col + card_width + 220,
                                         card_width, card_height)
                self.foundations.append(FoundationStack(foundation_rect_3, CardImage.pile_card_image))
            if col == 3:
                foundation_rect_3 = Rect(spacing + 15, spacing * col + card_width + 365,
                                         card_width, card_height)
                self.foundations.append(FoundationStack(foundation_rect_3, CardImage.pile_card_image))

        # Tableau piles
        self.tablo = []
        index = 0
        for val in range(0, 7):
            tableau_rect = Rect(spacing + (spacing + card_width) * val + 170, 2 * spacing, card_width,
                                card_height)
            self.tablo.append(TableauStack(tableau_rect, CardImage.pile_card_image, tableau_spacing))
            for col in range(0, val + 1):
                self.tablo[val].add_card(cards[index])
                index += 1

        # add cards in the second column of deck
        for i in range(28, 32):
            cards[i].show()
            self.tablo[1].add_card(cards[i])

        # add cards in the third column of deck
        for j in range(33, 37):
            cards[j].show()
            self.tablo[2].add_card(cards[j])

        # add cards in the fourth column of deck
        for k in range(37, 41):
            x = random.Random(k)
            cards[k].show()
            self.tablo[3].add_card(cards[k])

        # add cards in the fifth column of deck
        for l in range(41, 45):
            cards[l].show()
            self.tablo[4].add_card(cards[l])

        # add cards in the sixth column of deck
        for m in range(45, 49):
            cards[m].show()
            self.tablo[5].add_card(cards[m])

        # add cards in the seventh column of deck
        for n in range(49, 52):
            cards[n].show()
            self.tablo[6].add_card(cards[n])
        self.tablo[6].add_card(cards[51])

        c = 0
        for i in range(len(self.tablo)):
            c += len(self.tablo[i])

    def update(self):

        for event in pg.event.get():
            if event.type == QUIT:
                self.want_to_quit = True
            elif event.type == MOUSEBUTTONDOWN:
                draggable_stack = self.tablo + self.foundations
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

        for tableau in self.tablo:
            tableau.update()

    def render(self):
        self.screen.blit(self.background, (0, 0))
        score = Score(self.screen, 350, 520)
        score.message_display(self.score)

        for foundation in self.foundations:
            foundation.draw(self.screen)

        for tableau in self.tablo:
            tableau.draw(self.screen)

        for card_sprite in self.dragged_cards:
            self.screen.blit(card_sprite.image, card_sprite.rect)

        pg.display.flip()

    def run(self):
        while not self.want_to_quit:
            self.update()
            self.render()
