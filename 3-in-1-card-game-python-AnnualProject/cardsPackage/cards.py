import pygame as pg
from pygame.locals import *

from cardsPackage.cardImage import CardImage


class Cards(pg.sprite.Sprite):

    def __init__(self, suit, value, visible, pos=(0, 0)):
        """
        constructeur
        :param suit:
        :param value:
        :param visible:
        :param pos:
        """
        pg.sprite.Sprite.__init__(self)
        self.suit = suit
        self.value = value
        self.visible = visible
        self.image = CardImage.get_card_asset(self.suit, self.value, self.visible)
        self.rect = pg.Rect(pos[0], pos[1], self.image.get_width(), self.image.get_height())
        self.isMoving = False
        self.last_pos = pos

    def move(self, position):
        """
        méthode de déplacement des cartes
        :param position:
        :return:
        """
        self.rect.x += position[0]
        self.rect.y += position[1]

    def update_image(self):
        """
        méthode mettant à jour l'affichage de nos cartes
        :return:
        """
        self.image = CardImage.get_card_asset(self.suit, self.value, self.visible)

    def hide(self):
        """
        méthode permettant de cacher une carte
        :return:
        """
        self.visible = False
        self.update_image()

    def show(self):
        """
        méthode permettant d'afficher une carte
        :return:
        """
        self.visible = True
        self.update_image()

    @staticmethod
    def is_valid_tableau_append(tableau_cards, new_card):
        if not tableau_cards:
            if new_card.value == 13:
                return True
        elif (abs(tableau_cards[-1].suit.value - new_card.suit.value) % 2 != 0 and
              tableau_cards[-1].value - 1 == new_card.value):
            return True

        return False
