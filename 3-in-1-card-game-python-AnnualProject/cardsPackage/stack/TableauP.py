from enum import Enum
import pygame as pg
from pygame._sprite import Group

from cardsPackage.cards import *


class TableauStack(Group):
    def __init__(self, rect, image, card_spacing=30):
        Group.__init__(self)
        self.rect = rect
        self.image = image
        self.spacing = card_spacing

    def add_card(self, card):
        if not card.visible:
            if self.sprites():
                self.sprites()[-1].hide()
            card.show()

        card.rect.x = self.rect.x
        card.rect.y = self.rect.y + self.spacing * len(self.sprites())

        if self.sprites():
            self.rect.height += self.spacing
        self.add(self, card)

    def remove_card(self, card):
        self.remove(card)
        if self.sprites():
            self.rect.height -= self.spacing

    def collidepoint(self, point):
        return self.rect.collidepoint(point)

    def start_drag(self, mouse_pos):
        card_start = None

        for sprite in reversed(self.sprites()):
            if sprite.rect.collidepoint(mouse_pos) and sprite.visible:
                card_start = sprite
                break

        card_drag_list = []
        if card_start:
            card_drag_list = self.sprites()[self.sprites().index(card_start):]
            for card_sprite in card_drag_list:
                self.remove_card(card_sprite)

        #print(card_drag_list)
        return card_drag_list

    def end_drag(self):
        if self.sprites():
            self.sprites()[-1].show()
        print("Card dragged")

    def drop(self, cards):
        if Cards.is_valid_tableau_append(self.sprites(), cards[0]):
            for card in cards:
                self.add_card(card)
            return True
        else:
            return False

    def draw(self, surf):
        if not self.sprites():
            surf.blit(self.image, (self.rect.x, self.rect.y))
        Group.draw(self, surf)
